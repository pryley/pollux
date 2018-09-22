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

## [1.2.0] - 2018-09-23

### Added
- Added ability to use arrays in condition values
- Added plugin documentation

### Fixed
- Fixed compatibility with some Meta-box addons

## [1.1.4] - 2018-07-04

### Fixed
- Fixed possible activation check conflict

## [1.1.3] - 2018-02-19

### Fixed
- Fixed plugin deactivation on unsupported systems

## [1.1.2] - 2017-10-04

### Fixed
- Fixed archive settings

## [1.1.1] - 2017-10-04

### Fixed
- Added compatibility with Give WP plugin
- Fixed plugin localization

## [1.1.0] - 2017-08-12

### Added
- Load pollux-hooks.php if it exists
- Show permalink in archive pages

### Changed
- An unknown column value is now '&mdash;'
- Column thumbnail image is now the builtin 'thumbnail' size
- Restricted column thumbnail max height/width to 64px

### Fixed
- Fixed a "SiteMeta" helper bug
- Fixed PostMeta::get() to allow `(array) get_query_var('post_type')` as the group.
- Fixed Settings meta-box fields that have multiple values (i.e. checkboxes)
- Fixed taxonomy meta-box fields from incorrectly showing in the instructions meta-box

## [1.0.3] - 2017-05-19

### Fixed
- Fixed post_type detection on post-new.php
- Recompile config on reset

## [1.0.2] - 2017-05-16

### Added
- Show admin notices for YAML parse errors

### Removed
- Replaced demo *.yml files in favour of github wiki pages

### Fixed
- Fixed getCurrentScreen()

## [1.0.1] - 2017-05-15

### Removed
- Disabled raw strings until they can be parsed properly without using eval()

### Fixed
- Fixed headings and divider fields from incorrectly showing in the instructions meta-box
- Fixed negative conditions

## [1.0.0] - 2017-05-13

- Initial plugin release
