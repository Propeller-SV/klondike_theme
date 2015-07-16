<?php
require_once ABSPATH . 'wp-admin/includes/template.php';

if ( !function_exists('add_languages_polylang') ) {
	function add_languages_polylang() {
		if ( is_plugin_active( 'polylang/polylang.php' ) ) {
			$options = get_option('polylang');

			// redirect the language page to the homepage
			$options['redirect_lang'] = 1;

			// remove URL language information for default language
			$options['hide_default'] = 1;
			update_option('polylang', $options);

			$model = new PLL_Admin_Model($options);

			$languages = array(
				array( 'name' => 'English', 'slug' => 'en', 'locale' => 'en_US', 'rtl' => 0, 'term_group' => 1),
				// array( 'name' => 'Português', 'slug' => 'pt', 'locale' => 'pt_PT', 'rtl' => 0, 'term_group' => 2),
			);
			foreach ($languages as $language) {
				$language_installed = pll_is_language_installed($language['slug']);
				if (!$language_installed) {
					$model->add_language($language);
				}
			}
			// fills existing posts & terms with default language
			$nolang = $model->get_objects_with_no_lang();
			if (!empty($nolang['posts']))
				$model->set_language_in_mass('post', $nolang['posts'], $options['default_lang']);
			if (!empty($nolang['terms']))
				$model->set_language_in_mass('term', $nolang['terms'], $options['default_lang']);
		}

	}
}

function pll_is_language_installed($language_code) {
	global $polylang;
	$languages = $polylang->model->get_languages_list();
	foreach ($languages as $language) {
		if ($language->slug == $language_code) {
			return true;
		}
	}
	return false;
}

add_action( 'after_switch_theme', 'add_languages_polylang' );
?>
