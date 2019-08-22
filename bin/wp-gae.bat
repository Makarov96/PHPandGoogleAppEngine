@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../google/cloud-tools/src/Utils/WordPress/wp-gae
php "%BIN_TARGET%" %*
