<?php
/**
 * Copyright 2017 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\PubSub;

use Google\Cloud\Core\ArrayTrait;
use Google\Cloud\Core\Duration;
use Google\Cloud\Core\Exception\NotFoundException;
use Google\Cloud\Core\Iam\Iam;
use Google\Cloud\Core\Timestamp;
use Google\Cloud\Core\TimeTrait;
use Google\Cloud\Core\ValidateTrait;
use Google\Cloud\PubSub\Connection\ConnectionInterface;
use Google\Cloud\PubSub\Connection\IamSubscription;
use Google\Cloud\PubSub\IncomingMessageTrait;
use InvalidArgumentException;

/**
 * A named resource representing the stream of messages from a single, specific
 * topic, to be delivered to the subscribing application.
 *
 * Example:
 * ```
 * // Create subscription through a topic
 * use Google\Cloud\PubSub\PubSubClient;
 *
 * $pubsub = new PubSubClient();
 *
 * $topic = $pubsub->topic('my-new-topic');
 *
 * $subscription = $topic->subscription('my-new-subscription');
 * ```
 *
 * ```
 * // Create subscription through PubSubClient
 * use Google\Cloud\PubSub\PubSubClient;
 *
 * $pubsub = new PubSubClient();
 *
 * $subscription = $pubsub->subscription(
 *     'my-new-subscription',
 *     'my-new-topic'
 * );
 * ```
 */
class Subscription
{
    use ArrayTrait;
    use IncomingMessageTrait;
    use ResourceNameTrait;
    use TimeTrait;
    use ValidateTrait;

    const MAX_MESSAGES = 1000;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var string
     */
    private $projectId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $topicName;

    /**
     * @var bool
     */
    private $encode;

    /**
     * @var array
     */
    private $info;

    /**
     * @var Iam
     */
    private $iam;

    /**
     * Create a Subscription.
     *
     * The idiomatic way to use this class is through the PubSubClient or Topic,
     * but you can instantiate it directly as well.
     *
     * @param ConnectionInterface $connection The service connection object
     * @param string $projectId The current project
     * @param string $name The subscription name
     * @param string $topicName The topic name the subscription is attached to
     * @param bool $encode Whether messages are encrypted or not.
     * @param array $info [optional] Subscription info. Used to pre-populate the object.
     */
    public function __construct(
        ConnectionInterface $connection,
        $projectId,
        $name,
        $topicName,
        $encode,
        array $info = []
    ) {
        $this->connection = $connection;
        $this->projectId = $projectId;
        $this->encode = (bool) $encode;
        $this->info = $info;

        // Accept either a simple name or a fully-qualified name.
        if ($this->isFullyQualifiedName('subscription', $name)) {
            $this->name = $name;
        } else {
            $this->name = $this->formatName('subscription', $name, $projectId);
        }

        // Accept either a simple name or a fully-qualified name.
        if ($this->isFullyQualifiedName('topic', $topicName)) {
            $this->topicName = $topicName;
        } else {
            $this->topicName = !is_null($topicName)
                ? $this->formatName('topic', $topicName, $projectId)
                : null;
        }
    }

    /**
     * Get the subscription name
     *
     * Example:
     * ```
     * echo $subscription->name();
     * ```
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Execute a service request creating the subscription.
     *
     * The suggested way of creating a subscription is by calling through
     * {@see Google\Cloud\PubSub\Topic::subscribe()} or {@see Google\Cloud\PubSub\Topic::subscription()}.
     *
     * Returns subscription info in the format detailed in the documentation
     * for a [subscription](https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions#Subscription).
     *
     * **NOTE: Some methods of instantiation of a Subscription do not supply a
     * topic name. The topic name is required to create a subscription.**
     *
     * Example:
     * ```
     * $topic = $pubsub->topic('my-new-topic');
     *
     * $subscription = $topic->subscription('my-new-subscription');
     * $result = $subscription->create();
     * ```
     *
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/create Create Subscription
     *
     * @param array $options [optional] {
     *     Configuration Options
     *
     *     For information regarding the push configuration settings, see
     *     [PushConfig](https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions#PushConfig).
     *
     *     @type string $pushConfig.pushEndpoint A URL locating the endpoint to which
     *           messages should be pushed. For example, a Webhook endpoint
     *           might use "https://example.com/push".
     *     @type array $pushConfig.attributes Endpoint configuration attributes.
     *     @type array $pushConfig.oidcToken Contains information needed for generating
     *           an OpenIDConnect token.
     *     @type int $ackDeadlineSeconds The maximum time after a subscriber
     *           receives a message before the subscriber should acknowledge the
     *           message.
     *     @type bool $retainAckedMessages Indicates whether to retain
     *           acknowledged messages.
     *     @type Duration|string $messageRetentionDuration How long to retain
     *           unacknowledged messages in the subscription's backlog, from the
     *           moment a message is published. If `$retainAckedMessages` is
     *           true, then this also configures the retention of acknowledged
     *           messages, and thus configures how far back in time a `Seek`
     *           can be done. Cannot be more than 7 days or less than 10 minutes.
     *           If a string is provided, it should be as a duration in seconds
     *           with up to nine fractional digits, terminated by 's', e.g
     *           "3.5s". **Defaults to** 7 days.
     *     @type array $expirationPolicy A policy that specifies the conditions
     *           for resource expiration (i.e., automatic resource deletion).
     *     @type Duration|string $expirationPolicy.ttl Specifies the
     *           "time-to-live" duration for an associated resource. The
     *           resource expires if it is not active for a period of `ttl`. The
     *           definition of "activity" depends on the type of the associated
     *           resource. The minimum and maximum allowed values for `ttl`
     *           depend on the type of the associated resource, as well. If
     *           `ttl` is not set, the associated resource never expires. If a
     *           string is provided, it should be as a duration in seconds with
     *           up to nine fractional digits, terminated by 's', e.g "3.5s".
     * }
     * @return array An array of subscription info
     * @throws \InvalidArgumentException
     */
    public function create(array $options = [])
    {
        // If a subscription is created via PubSubClient::subscription(),
        // it may or may not have a topic name. This is fine for most API
        // interactions, but a topic name is required to create a subscription.
        if (!$this->topicName) {
            throw new InvalidArgumentException(
                'A topic name is required to create a subscription.'
            );
        }

        $options = $this->formatSubscriptionDurations($options);

        $this->info = $this->connection->createSubscription($options + [
            'name' => $this->name,
            'topic' => $this->topicName
        ]);

        return $this->info;
    }

    /**
     * Update the subscription.
     *
     * Note that subscription name and topic are immutable properties and may
     * not be modified.
     *
     * Example:
     * ```
     * $subscription->update([
     *     'retainAckedMessages' => true
     * ]);
     * ```
     *
     * ```
     * // Updating labels and push config attributes with explicit update masks.
     * $subscription->update([
     *     'labels' => [
     *         'label-1' => 'value'
     *     ],
     *     'pushConfig' => [
     *         'attributes' => [
     *             'x-goog-version' => 1
     *         ]
     *     ]
     * ], [
     *     'updateMask' => [
     *         'labels',
     *         'pushConfig.attributes'
     *     ]
     * ]);
     * ```
     *
     * @param array $subscription {
     *     The Subscription data.
     *
     *     For information regarding the push configuration settings, see
     *     [PushConfig](https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions#PushConfig).
     *
     *     @type string $pushConfig.pushEndpoint A URL locating the endpoint to which
     *           messages should be pushed. For example, a Webhook endpoint
     *           might use "https://example.com/push".
     *     @type array $pushConfig.attributes Endpoint configuration attributes.
     *     @type int $ackDeadlineSeconds The maximum time after a subscriber
     *           receives a message before the subscriber should acknowledge the
     *           message.
     *     @type bool $retainAckedMessages Indicates whether to retain
     *           acknowledged messages.
     *     @type Duration $messageRetentionDuration How long to retain
     *           unacknowledged messages in the subscription's backlog, from the
     *           moment a message is published. If `$retainAckedMessages` is
     *           true, then this also configures the retention of acknowledged
     *           messages, and thus configures how far back in time a `Seek`
     *           can be done. Cannot be more than 7 days or less than 10 minutes.
     *           **Defaults to** 7 days.
     *     @type array $expirationPolicy A policy that specifies the conditions
     *           for resource expiration (i.e., automatic resource deletion).
     *     @type Duration|string $expirationPolicy.ttl Specifies the
     *           "time-to-live" duration for an associated resource. The
     *           resource expires if it is not active for a period of `ttl`. The
     *           definition of "activity" depends on the type of the associated
     *           resource. The minimum and maximum allowed values for `ttl`
     *           depend on the type of the associated resource, as well. If
     *           `ttl` is not set, the associated resource never expires. If a
     *           string is provided, it should be as a duration in seconds with
     *           up to nine fractional digits, terminated by 's', e.g "3.5s".
     *     @type array $updateMask A list of field paths to be modified. Nested
     *           key names should be dot-separated, e.g. `pushConfig.pushEndpoint`.
     *           Google Cloud PHP will attempt to infer this value on your
     *           behalf, however modification of map fields with arbitrary keys
     *           (such as labels or push config attributes) requires an explicit
     *           update mask.
     * }
     * @param array $options [optional] Configuration options.
     * @return array The subscription info.
     */
    public function update(array $subscription, array $options = [])
    {
        $updateMaskPaths = $this->pluck('updateMask', $options, false) ?: [];
        if (!$updateMaskPaths) {
            $excludes = ['name', 'topic'];
            $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($subscription));
            foreach ($iterator as $leafValue) {
                $keys = [];
                foreach (range(0, $iterator->getDepth()) as $depth) {
                    $keys[] = $iterator->getSubIterator($depth)->key();
                }

                $path = implode('.', $keys);
                if (!in_array($path, $excludes)) {
                    $updateMaskPaths[] = $path;
                }
            }
        }

        $subscription = $this->formatSubscriptionDurations($subscription);

        return $this->info = $this->connection->updateSubscription([
            'name' => $this->name,
            'subscription' => [
                'name' => $this->name
            ] + $subscription,
            'updateMask' => implode(',', $updateMaskPaths)
        ] + $options);
    }

    /**
     * Delete a subscription
     *
     * Example:
     * ```
     * $subscription->delete();
     * ```
     *
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/delete Delete Subscription
     *
     * @param array $options [optional] Configuration Options.
     * @return void
     */
    public function delete(array $options = [])
    {
        $this->connection->deleteSubscription($options + [
            'subscription' => $this->name
        ]);
    }

    /**
     * Check if a subscription exists.
     *
     * Service errors will NOT bubble up from this method. It will always return
     * a boolean value. If you want to check for errors, use
     * {@see Google\Cloud\PubSub\Subscription::info()}.
     *
     * If you need to re-check the existence of a subscription that is already
     * downloaded, call {@see Google\Cloud\PubSub\Subscription::reload()} first
     * to refresh the cached information.
     *
     * Example:
     * ```
     * if ($subscription->exists()) {
     *     echo 'Subscription exists!';
     * }
     * ```
     *
     * @param array $options [optional] Configuration Options
     * @return bool
     */
    public function exists(array $options = [])
    {
        try {
            $this->info($options);
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * Get info on a subscription
     *
     * If the info is already cached on the object, it will return that result.
     * To fetch a fresh result, use {@see Google\Cloud\PubSub\Subscription::reload()}.
     *
     * Example:
     * ```
     * $info = $subscription->info();
     * echo $info['name']; // `projects/my-awesome-project/subscriptions/my-new-subscription`
     * ```
     *
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/get Get Subscription
     *
     * @param array $options [optional] Configuration Options
     * @return array Subscription data
     */
    public function info(array $options = [])
    {
        if (!$this->info) {
            $this->reload($options);
        }

        return $this->info;
    }

    /**
     * Retrieve info on a subscription from the API.
     *
     * To use the previously cached result (if it exists), use
     * {@see Subscription::info()}.
     *
     * Example:
     * ```
     * $subscription->reload();
     * $info = $subscription->info();
     * echo $info['name']; // `projects/my-awesome-project/subscriptions/my-new-subscription`
     * ```
     *
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/get Get Subscription
     *
     * @param array $options [optional] Configuration Options
     * @return array Subscription data
     */
    public function reload(array $options = [])
    {
        return $this->info = $this->connection->getSubscription($options + [
            'subscription' => $this->name
        ]);
    }

    /**
     * Retrieve new messages from the topic.
     *
     * Example:
     * ```
     * $messages = $subscription->pull();
     * foreach ($messages as $message) {
     *     echo $message->data();
     * }
     * ```
     *
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/pull Pull Subscriptions
     *
     * @param array $options [optional] {
     *      Configuration Options
     *
     *      @type bool $returnImmediately If true, the system will respond
     *            immediately, even if no messages are available. Otherwise,
     *            wait until new messages are available. **Defaults to**
     *            `false`.
     *      @type int $maxMessages Limit the amount of messages pulled.
     *            **Defaults to** `1000`.
     * }
     * @return Message[]
     */
    public function pull(array $options = [])
    {
        $messages = [];
        $options['returnImmediately'] = isset($options['returnImmediately'])
            ? $options['returnImmediately']
            : false;
        $options['maxMessages'] = isset($options['maxMessages'])
            ? $options['maxMessages']
            : self::MAX_MESSAGES;

        $response = $this->connection->pull($options + [
            'subscription' => $this->name
        ]);

        if (isset($response['receivedMessages'])) {
            foreach ($response['receivedMessages'] as $message) {
                $messages[] = $this->messageFactory($message, $this->connection, $this->projectId, $this->encode);
            }
        }

        return $messages;
    }

    /**
     * Acknowledge receipt of a message.
     *
     * Use {@see Google\Cloud\PubSub\Subscription::acknowledgeBatch()} to
     * acknowledge multiple messages at once.
     *
     * Example:
     * ```
     * $messages = $subscription->pull();
     *
     * $subscription->acknowledge($messages[0]);
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/acknowledge Acknowledge Message
     * @codingStandardsIgnoreEnd
     *
     * @param Message $message A message object.
     * @param array $options [optional] Configuration Options
     * @return void
     */
    public function acknowledge(Message $message, array $options = [])
    {
        $this->acknowledgeBatch([$message], $options);
    }

    /**
     * Acknowledge receipt of multiple messages at once.
     *
     * Use {@see Google\Cloud\PubSub\Subscription::acknowledge()} to acknowledge
     * a single message.
     *
     * Example:
     * ```
     * $messages = $subscription->pull();
     *
     * $subscription->acknowledgeBatch($messages);
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/acknowledge Acknowledge Message
     * @codingStandardsIgnoreEnd
     *
     * @param Message[] $messages An array of messages
     * @param array $options Configuration Options
     * @return void
     */
    public function acknowledgeBatch(array $messages, array $options = [])
    {
        $this->validateBatch($messages, Message::class);

        $this->connection->acknowledge($options + [
            'subscription' => $this->name,
            'ackIds' => $this->getMessageAckIds($messages)
        ]);
    }

    /**
     * Set the acknowledge deadline for a single ackId.
     *
     * Use {@see Google\Cloud\PubSub\Subscription::modifyAckDeadlineBatch()} to
     * modify the ack deadline for multiple messages at once.
     *
     * Example:
     * ```
     * $messages = $subscription->pull();
     *
     * foreach ($messages as $message) {
     *     $subscription->modifyAckDeadline($message, 3);
     *     sleep(2);
     *
     *     // Now we'll acknowledge!
     *     $subscription->acknowledge($message);
     *
     *     break;
     * }
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/modifyAckDeadline Modify Ack Deadline
     * @codingStandardsIgnoreEnd
     *
     * @param Message $message A message object
     * @param int $seconds The new ack deadline with respect to the time
     *        this request was sent to the Pub/Sub system. Must be >= 0. For
     *        example, if the value is 10, the new ack deadline will expire 10
     *        seconds after the ModifyAckDeadline call was made. Specifying
     *        zero may immediately make the message available for another pull
     *        request.
     * @param array $options [optional] Configuration Options
     * @return void
     */
    public function modifyAckDeadline(Message $message, $seconds, array $options = [])
    {
        $this->modifyAckDeadlineBatch([$message], $seconds, $options);
    }

    /**
     * Set the acknowledge deadline for multiple ackIds.
     *
     * Use {@see Google\Cloud\PubSub\Subscription::modifyAckDeadline()} to
     * modify the ack deadline for a single message.
     *
     * Example:
     * ```
     * $messages = $subscription->pull();
     *
     * // Set the ack deadline to three seconds from now for every message
     * $subscription->modifyAckDeadlineBatch($messages, 3);
     *
     * // Delay execution, or make a sandwich or something.
     * sleep(2);
     *
     * // Now we'll acknowledge
     * $subscription->acknowledgeBatch($messages);
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/modifyAckDeadline Modify Ack Deadline
     * @codingStandardsIgnoreEnd
     *
     * @param Message[] $messages An array of messages
     * @param int $seconds The new ack deadline with respect to the time
     *        this request was sent to the Pub/Sub system. Must be >= 0. For
     *        example, if the value is 10, the new ack deadline will expire 10
     *        seconds after the ModifyAckDeadline call was made. Specifying
     *        zero may immediately make the message available for another pull
     *        request.
     * @param array $options [optional] Configuration Options
     * @return void
     */
    public function modifyAckDeadlineBatch(array $messages, $seconds, array $options = [])
    {
        $this->validateBatch($messages, Message::class);

        $this->connection->modifyAckDeadline($options + [
            'subscription' => $this->name,
            'ackIds' => $this->getMessageAckIds($messages),
            'ackDeadlineSeconds' => $seconds
        ]);
    }

    /**
     * Set the push config for the subscription
     *
     * Example:
     * ```
     * $subscription->modifyPushConfig([
     *     'pushEndpoint' => 'https://www.example.com/foo/bar'
     * ]);
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/modifyPushConfig Modify Push Config
     * @codingStandardsIgnoreEnd
     *
     * @param array $pushConfig {
     *     Push delivery configuration. See
     *     [PushConfig](https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions#PushConfig)
     *     for more details.
     *
     *     @type string $pushEndpoint A URL locating the endpoint to which
     *           messages should be pushed. For example, a Webhook endpoint
     *           might use "https://example.com/push".
     *     @type array $attributes Endpoint configuration attributes.
     * }
     * @param array $options [optional] Configuration Options
     * @return void
     */
    public function modifyPushConfig(array $pushConfig, array $options = [])
    {
        $this->connection->modifyPushConfig($options + [
            'subscription' => $this->name,
            'pushConfig' => $pushConfig
        ]);
    }

    /**
     * Seek to a given timestamp.
     *
     * When you seek to a time, it has the effect of marking every message
     * received before this time as acknowledged, and all messages received
     * after the time as unacknowledged.
     *
     * Please note that this method may not yet be available in your project.
     *
     * Example:
     * ```
     * $time = $pubsub->timestamp(new \DateTime('2017-04-01'));
     * $subscription->seekToTime($time);
     * ```
     *
     * @param Timestamp $timestamp The time to seek to.
     * @return void
     */
    public function seekToTime(Timestamp $timestamp)
    {
        return $this->connection->seek([
            'subscription' => $this->name,
            'time' => $timestamp->formatAsString()
        ]);
    }

    /**
     * Seek to a given snapshot.
     *
     * When seeking to a snapshot, any message that had an "unacknowledged"
     * state when the snapshot was created can be re-delivered.
     *
     * Please note that this method may not yet be available in your project.
     *
     * Example:
     * ```
     * $snapshot = $pubsub->snapshot('my-snapshot');
     * $subscription->seekToSnapshot($snapshot);
     * ```
     *
     * @param Snapshot $snapshot The snapshot to seek to.
     * @return void
     */
    public function seekToSnapshot(Snapshot $snapshot)
    {
        return $this->connection->seek([
            'subscription' => $this->name,
            'snapshot' => $snapshot->name()
        ]);
    }

    /**
     * Manage the IAM policy for the current Subscription.
     *
     * Example:
     * ```
     * $iam = $subscription->iam();
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/pubsub/access_control PubSub Access Control Documentation
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/getIamPolicy Get Subscription IAM Policy
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/setIamPolicy Set Subscription IAM Policy
     * @see https://cloud.google.com/pubsub/docs/reference/rest/v1/projects.subscriptions/testIamPermissions Test Subscription Permissions
     * @codingStandardsIgnoreEnd
     *
     * @return Iam
     */
    public function iam()
    {
        if (!$this->iam) {
            $iamConnection = new IamSubscription($this->connection);
            $this->iam = new Iam($iamConnection, $this->name);
        }

        return $this->iam;
    }

    /**
     * Get a list of ackIds from a list of Message objects.
     *
     * @param Message[] $messages The messages
     * @return array
     */
    private function getMessageAckIds(array $messages)
    {
        $ackIds = [];
        foreach ($messages as $message) {
            $ackIds[] = $message->ackId();
        }

        return $ackIds;
    }

    /**
     * Format Duration objects for the API.
     *
     * @param array $options
     * @return array
     */
    private function formatSubscriptionDurations(array $options)
    {
        if (isset($options['messageRetentionDuration']) && $options['messageRetentionDuration'] instanceof Duration) {
            $duration = $options['messageRetentionDuration']->get();
            $options['messageRetentionDuration'] = sprintf(
                '%s.%ss',
                $duration['seconds'],
                $this->convertNanoSecondsToFraction($duration['nanos'], false)
            );
        }

        if (isset($options['expirationPolicy']['ttl']) && $options['expirationPolicy']['ttl'] instanceof Duration) {
            $duration = $options['expirationPolicy']['ttl']->get();
            $options['expirationPolicy']['ttl'] = sprintf(
                '%s.%ss',
                $duration['seconds'],
                $this->convertNanoSecondsToFraction($duration['nanos'], false)
            );
        }

        return $options;
    }

    /**
     * Present a nicer debug result to people using php 5.6 or greater.
     * @return array
     * @codeCoverageIgnore
     * @access private
     */
    public function __debugInfo()
    {
        return [
            'name' => $this->name,
            'topicName' => $this->topicName,
            'projectId' => $this->projectId,
            'info' => $this->info,
            'connection' => get_class($this->connection)
        ];
    }
}
