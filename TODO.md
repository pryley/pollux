## Fix
- [x] don't show headings and divider fields in the info meta-box
- [x] fix negative conditions
- [x] fix map field, must normalize the address_field key by adding the pollux prefix to it
- [x] show notices for YAML parse errors
- [ ] protect pollux.yml from direct access if inside webroot
- [ ] fix archive page reset link
- [ ] fix JS depends when depends element does not exist
- [ ] fix archive page metaboxes to restrict them to post_type
- [ ] make archive pages compatible with "The SEO Framework" metabox

## Core Features
- [x] Config
- [x] PostType
- [x] PostType Archive
- [x] Taxonomy
- [x] MetaBox
- [x] Conditional MetaBoxes
- [x] Conditional MetaBox Fields
- [x] Dependant MetaBox Fields
- [x] Settings
- [x] PostMeta
- [x] SiteMeta
- [x] Miscellaneous
- [x] Disable Posts
- [x] Settings page to change plugin config
- [x] GateKeeper (plugin depends)
- [ ] HELP tabs for pollux
- [ ] Add adminbar edit link when viewing archive pages on frontend
- [=] Write test suite

## Other Features
- [ ] Color Schemes

## Translatable Strings
- [ ] try/catch when including generated config array
- [ ] Parse raw strings without having to use eval()
- [ ] Auto-generate language file for config file


<?php
// handle REST API default
if ( null === $this->args['show_in_rest'] ) {
	if ( null !== $this->args['publicly_queryable'] ) {
		$this->args['show_in_rest'] = $this->args['publicly_queryable'];
	} elseif ( null !== $this->args['public'] ) {
		$this->args['show_in_rest'] = $this->args['public'];
	} else {
		$this->args['show_in_rest'] = false;
	}
}
