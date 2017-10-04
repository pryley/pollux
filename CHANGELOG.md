# Change Log

All notable changes to Pollux will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added

### Changed

### Deprecated

### Removed

### Fixed

### Security

## [1.1.1] - 2017-10-04

### Fixed
- Fix plugin localization

## [1.1.0] - 2017-08-12

### Added
- Load pollux-hooks.php if it exists
- Show permalink in archive pages

### Changed
- An unknown column value is now '&mdash;'
- Column thumbnail image is now the builtin 'thumbnail' size
- Restrict column thumbnail max height/width to 64px

### Fixed
- Fix a "SiteMeta" helper bug
- Fix PostMeta::get() to allow `(array) get_query_var('post_type')` as the group.
- Fix Settings meta-box fields that have multiple values (i.e. checkboxes)
- Fix taxonomy meta-box fields from incorrectly showing in the instructions meta-box

## [1.0.3] - 2017-05-19

### Fixed
- Fix post_type detection on post-new.php
- Recompile config on reset

## [1.0.2] - 2017-05-16

### Added
- Show admin notices for YAML parse errors

### Removed
- Replaced demo *.yml files in favour of github wiki pages

### Fixed
- Fix getCurrentScreen()

## [1.0.1] - 2017-05-15

### Removed
- Disabled raw strings until they can be parsed properly without using eval()

### Fixed
- Fix headings and divider fields from incorrectly showing in the instructions meta-box
- Fix negative conditions

## [1.0.0] - 2017-05-13

- Initial plugin release
