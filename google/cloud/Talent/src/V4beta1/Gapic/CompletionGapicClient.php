<?php
/*
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * GENERATED CODE WARNING
 * This file was generated from the file
 * https://github.com/google/googleapis/blob/master/google/cloud/talent/v4beta1/completion_service.proto
 * and updates to that file get reflected here through a refresh process.
 *
 * @experimental
 */

namespace Google\Cloud\Talent\V4beta1\Gapic;

use Google\ApiCore\ApiException;
use Google\ApiCore\CredentialsWrapper;
use Google\ApiCore\GapicClientTrait;
use Google\ApiCore\PathTemplate;
use Google\ApiCore\RequestParamsHeaderDescriptor;
use Google\ApiCore\RetrySettings;
use Google\ApiCore\Transport\TransportInterface;
use Google\ApiCore\ValidationException;
use Google\Auth\FetchAuthTokenInterface;
use Google\Cloud\Talent\V4beta1\CompleteQueryRequest;
use Google\Cloud\Talent\V4beta1\CompleteQueryRequest\CompletionScope;
use Google\Cloud\Talent\V4beta1\CompleteQueryRequest\CompletionType;
use Google\Cloud\Talent\V4beta1\CompleteQueryResponse;

/**
 * Service Description: A service handles auto completion.
 *
 * This class provides the ability to make remote calls to the backing service through method
 * calls that map to API methods. Sample code to get started:
 *
 * ```
 * $completionClient = new CompletionClient();
 * try {
 *     $formattedParent = $completionClient->tenantName('[PROJECT]', '[TENANT]');
 *     $query = '';
 *     $pageSize = 0;
 *     $response = $completionClient->completeQuery($formattedParent, $query, $pageSize);
 * } finally {
 *     $completionClient->close();
 * }
 * ```
 *
 * Many parameters require resource names to be formatted in a particular way. To assist
 * with these names, this class includes a format method for each type of name, and additionally
 * a parseName method to extract the individual identifiers contained within formatted names
 * that are returned by the API.
 *
 * @experimental
 */
class CompletionGapicClient
{
    use GapicClientTrait;

    /**
     * The name of the service.
     */
    const SERVICE_NAME = 'google.cloud.talent.v4beta1.Completion';

    /**
     * The default address of the service.
     */
    const SERVICE_ADDRESS = 'jobs.googleapis.com';

    /**
     * The default port of the service.
     */
    const DEFAULT_SERVICE_PORT = 443;

    /**
     * The name of the code generator, to be included in the agent header.
     */
    const CODEGEN_NAME = 'gapic';

    /**
     * The default scopes required by the service.
     */
    public static $serviceScopes = [
        'https://www.googleapis.com/auth/cloud-platform',
        'https://www.googleapis.com/auth/jobs',
    ];
    private static $companyNameTemplate;
    private static $tenantNameTemplate;
    private static $pathTemplateMap;

    private static function getClientDefaults()
    {
        return [
            'serviceName' => self::SERVICE_NAME,
            'apiEndpoint' => self::SERVICE_ADDRESS.':'.self::DEFAULT_SERVICE_PORT,
            'clientConfig' => __DIR__.'/../resources/completion_client_config.json',
            'descriptorsConfigPath' => __DIR__.'/../resources/completion_descriptor_config.php',
            'gcpApiConfigPath' => __DIR__.'/../resources/completion_grpc_config.json',
            'credentialsConfig' => [
                'scopes' => self::$serviceScopes,
            ],
            'transportConfig' => [
                'rest' => [
                    'restClientConfigPath' => __DIR__.'/../resources/completion_rest_client_config.php',
                ],
            ],
        ];
    }

    private static function getCompanyNameTemplate()
    {
        if (null == self::$companyNameTemplate) {
            self::$companyNameTemplate = new PathTemplate('projects/{project}/tenants/{tenant}/companies/{company}');
        }

        return self::$companyNameTemplate;
    }

    private static function getTenantNameTemplate()
    {
        if (null == self::$tenantNameTemplate) {
            self::$tenantNameTemplate = new PathTemplate('projects/{project}/tenants/{tenant}');
        }

        return self::$tenantNameTemplate;
    }

    private static function getPathTemplateMap()
    {
        if (null == self::$pathTemplateMap) {
            self::$pathTemplateMap = [
                'company' => self::getCompanyNameTemplate(),
                'tenant' => self::getTenantNameTemplate(),
            ];
        }

        return self::$pathTemplateMap;
    }

    /**
     * Formats a string containing the fully-qualified path to represent
     * a company resource.
     *
     * @param string $project
     * @param string $tenant
     * @param string $company
     *
     * @return string The formatted company resource.
     * @experimental
     */
    public static function companyName($project, $tenant, $company)
    {
        return self::getCompanyNameTemplate()->render([
            'project' => $project,
            'tenant' => $tenant,
            'company' => $company,
        ]);
    }

    /**
     * Formats a string containing the fully-qualified path to represent
     * a tenant resource.
     *
     * @param string $project
     * @param string $tenant
     *
     * @return string The formatted tenant resource.
     * @experimental
     */
    public static function tenantName($project, $tenant)
    {
        return self::getTenantNameTemplate()->render([
            'project' => $project,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Parses a formatted name string and returns an associative array of the components in the name.
     * The following name formats are supported:
     * Template: Pattern
     * - company: projects/{project}/tenants/{tenant}/companies/{company}
     * - tenant: projects/{project}/tenants/{tenant}.
     *
     * The optional $template argument can be supplied to specify a particular pattern, and must
     * match one of the templates listed above. If no $template argument is provided, or if the
     * $template argument does not match one of the templates listed, then parseName will check
     * each of the supported templates, and return the first match.
     *
     * @param string $formattedName The formatted name string
     * @param string $template      Optional name of template to match
     *
     * @return array An associative array from name component IDs to component values.
     *
     * @throws ValidationException If $formattedName could not be matched.
     * @experimental
     */
    public static function parseName($formattedName, $template = null)
    {
        $templateMap = self::getPathTemplateMap();

        if ($template) {
            if (!isset($templateMap[$template])) {
                throw new ValidationException("Template name $template does not exist");
            }

            return $templateMap[$template]->match($formattedName);
        }

        foreach ($templateMap as $templateName => $pathTemplate) {
            try {
                return $pathTemplate->match($formattedName);
            } catch (ValidationException $ex) {
                // Swallow the exception to continue trying other path templates
            }
        }
        throw new ValidationException("Input did not match any known format. Input: $formattedName");
    }

    /**
     * Constructor.
     *
     * @param array $options {
     *                       Optional. Options for configuring the service API wrapper.
     *
     *     @type string $serviceAddress
     *           **Deprecated**. This option will be removed in a future major release. Please
     *           utilize the `$apiEndpoint` option instead.
     *     @type string $apiEndpoint
     *           The address of the API remote host. May optionally include the port, formatted
     *           as "<uri>:<port>". Default 'jobs.googleapis.com:443'.
     *     @type string|array|FetchAuthTokenInterface|CredentialsWrapper $credentials
     *           The credentials to be used by the client to authorize API calls. This option
     *           accepts either a path to a credentials file, or a decoded credentials file as a
     *           PHP array.
     *           *Advanced usage*: In addition, this option can also accept a pre-constructed
     *           {@see \Google\Auth\FetchAuthTokenInterface} object or
     *           {@see \Google\ApiCore\CredentialsWrapper} object. Note that when one of these
     *           objects are provided, any settings in $credentialsConfig will be ignored.
     *     @type array $credentialsConfig
     *           Options used to configure credentials, including auth token caching, for the client.
     *           For a full list of supporting configuration options, see
     *           {@see \Google\ApiCore\CredentialsWrapper::build()}.
     *     @type bool $disableRetries
     *           Determines whether or not retries defined by the client configuration should be
     *           disabled. Defaults to `false`.
     *     @type string|array $clientConfig
     *           Client method configuration, including retry settings. This option can be either a
     *           path to a JSON file, or a PHP array containing the decoded JSON data.
     *           By default this settings points to the default client config file, which is provided
     *           in the resources folder.
     *     @type string|TransportInterface $transport
     *           The transport used for executing network requests. May be either the string `rest`
     *           or `grpc`. Defaults to `grpc` if gRPC support is detected on the system.
     *           *Advanced usage*: Additionally, it is possible to pass in an already instantiated
     *           {@see \Google\ApiCore\Transport\TransportInterface} object. Note that when this
     *           object is provided, any settings in $transportConfig, and any `$apiEndpoint`
     *           setting, will be ignored.
     *     @type array $transportConfig
     *           Configuration options that will be used to construct the transport. Options for
     *           each supported transport type should be passed in a key for that transport. For
     *           example:
     *           $transportConfig = [
     *               'grpc' => [...],
     *               'rest' => [...]
     *           ];
     *           See the {@see \Google\ApiCore\Transport\GrpcTransport::build()} and
     *           {@see \Google\ApiCore\Transport\RestTransport::build()} methods for the
     *           supported options.
     * }
     *
     * @throws ValidationException
     * @experimental
     */
    public function __construct(array $options = [])
    {
        $clientOptions = $this->buildClientOptions($options);
        $this->setClientOptions($clientOptions);
    }

    /**
     * Completes the specified prefix with keyword suggestions.
     * Intended for use by a job search auto-complete search box.
     *
     * Sample code:
     * ```
     * $completionClient = new CompletionClient();
     * try {
     *     $formattedParent = $completionClient->tenantName('[PROJECT]', '[TENANT]');
     *     $query = '';
     *     $pageSize = 0;
     *     $response = $completionClient->completeQuery($formattedParent, $query, $pageSize);
     * } finally {
     *     $completionClient->close();
     * }
     * ```
     *
     * @param string $parent Required. Resource name of tenant the completion is performed within.
     *
     * The format is "projects/{project_id}/tenants/{tenant_id}", for example,
     * "projects/api-test-project/tenant/foo".
     *
     * Tenant id is optional and the default tenant is used if unspecified, for
     * example, "projects/api-test-project".
     * @param string $query Required. The query used to generate suggestions.
     *
     * The maximum number of allowed characters is 255.
     * @param int $pageSize Required. Completion result count.
     *
     * The maximum allowed page size is 10.
     * @param array $optionalArgs {
     *                            Optional.
     *
     *     @type string[] $languageCodes
     *          Optional. The list of languages of the query. This is
     *          the BCP-47 language code, such as "en-US" or "sr-Latn".
     *          For more information, see
     *          [Tags for Identifying Languages](https://tools.ietf.org/html/bcp47).
     *
     *          For
     *          [CompletionType.JOB_TITLE][google.cloud.talent.v4beta1.CompleteQueryRequest.CompletionType.JOB_TITLE]
     *          type, only open jobs with the same
     *          [language_codes][google.cloud.talent.v4beta1.CompleteQueryRequest.language_codes]
     *          are returned.
     *
     *          For
     *          [CompletionType.COMPANY_NAME][google.cloud.talent.v4beta1.CompleteQueryRequest.CompletionType.COMPANY_NAME]
     *          type, only companies having open jobs with the same
     *          [language_codes][google.cloud.talent.v4beta1.CompleteQueryRequest.language_codes]
     *          are returned.
     *
     *          For
     *          [CompletionType.COMBINED][google.cloud.talent.v4beta1.CompleteQueryRequest.CompletionType.COMBINED]
     *          type, only open jobs with the same
     *          [language_codes][google.cloud.talent.v4beta1.CompleteQueryRequest.language_codes]
     *          or companies having open jobs with the same
     *          [language_codes][google.cloud.talent.v4beta1.CompleteQueryRequest.language_codes]
     *          are returned.
     *
     *          The maximum number of allowed characters is 255.
     *     @type string $company
     *          Optional. If provided, restricts completion to specified company.
     *
     *          The format is
     *          "projects/{project_id}/tenants/{tenant_id}/companies/{company_id}", for
     *          example, "projects/api-test-project/tenants/foo/companies/bar".
     *
     *          Tenant id is optional and the default tenant is used if unspecified, for
     *          example, "projects/api-test-project/companies/bar".
     *     @type int $scope
     *          Optional. The scope of the completion. The defaults is
     *          [CompletionScope.PUBLIC][google.cloud.talent.v4beta1.CompleteQueryRequest.CompletionScope.PUBLIC].
     *          For allowed values, use constants defined on {@see \Google\Cloud\Talent\V4beta1\CompleteQueryRequest\CompletionScope}
     *     @type int $type
     *          Optional. The completion topic. The default is
     *          [CompletionType.COMBINED][google.cloud.talent.v4beta1.CompleteQueryRequest.CompletionType.COMBINED].
     *          For allowed values, use constants defined on {@see \Google\Cloud\Talent\V4beta1\CompleteQueryRequest\CompletionType}
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\Cloud\Talent\V4beta1\CompleteQueryResponse
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function completeQuery($parent, $query, $pageSize, array $optionalArgs = [])
    {
        $request = new CompleteQueryRequest();
        $request->setParent($parent);
        $request->setQuery($query);
        $request->setPageSize($pageSize);
        if (isset($optionalArgs['languageCodes'])) {
            $request->setLanguageCodes($optionalArgs['languageCodes']);
        }
        if (isset($optionalArgs['company'])) {
            $request->setCompany($optionalArgs['company']);
        }
        if (isset($optionalArgs['scope'])) {
            $request->setScope($optionalArgs['scope']);
        }
        if (isset($optionalArgs['type'])) {
            $request->setType($optionalArgs['type']);
        }

        $requestParams = new RequestParamsHeaderDescriptor([
          'parent' => $request->getParent(),
        ]);
        $optionalArgs['headers'] = isset($optionalArgs['headers'])
            ? array_merge($requestParams->getHeader(), $optionalArgs['headers'])
            : $requestParams->getHeader();

        return $this->startCall(
            'CompleteQuery',
            CompleteQueryResponse::class,
            $optionalArgs,
            $request
        )->wait();
    }
}
