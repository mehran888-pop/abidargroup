<?php
/**
 * Trilingual engine: Persian (fa), English (en) and Chinese (zh).
 *
 * The theme ships with a self-contained language layer so the site is fully
 * bilingual out of the box without a translation plugin. When WPML or Polylang
 * is active the engine automatically defers to the plugin language instead, so
 * the two can be mixed safely.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Abidar_Multilingual
 */
class Abidar_Multilingual {

	/**
	 * Singleton instance.
	 *
	 * @var Abidar_Multilingual|null
	 */
	protected static $instance = null;

	/**
	 * Supported languages.
	 *
	 * @var array
	 */
	protected $languages = array();

	/**
	 * Resolved language for the current request.
	 *
	 * @var string
	 */
	protected $current = '';

	/**
	 * Get the singleton.
	 *
	 * @return Abidar_Multilingual
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->languages = array(
			'fa' => array(
				'code'   => 'fa',
				'locale' => 'fa_IR',
				'html'   => 'fa-IR',
				'label'  => 'فارسی',
				'english_label' => 'Persian',
				'english' => 'Persian',
				'dir'    => 'rtl',
				'flag'   => '🇮🇷',
				'short'  => 'FA',
				'default' => true,
			),
			'en' => array(
				'code'   => 'en',
				'locale' => 'en_US',
				'html'   => 'en',
				'label'  => 'English',
				'english_label' => 'English',
				'english' => 'English',
				'dir'    => 'ltr',
				'flag'   => '🇬🇧',
				'short'  => 'EN',
				'default' => false,
			),
			'zh' => array(
				'code'   => 'zh',
				'locale' => 'zh_CN',
				'html'   => 'zh-CN',
				'label'  => '中文',
				'english_label' => 'Chinese',
				'english' => 'Chinese',
				'dir'    => 'ltr',
				'flag'   => '🇨🇳',
				'short'  => 'ZH',
				'default' => false,
			),
		);
	}

	/**
	 * Available languages.
	 *
	 * @return array
	 */
	public function languages() {
		return apply_filters( 'abidar_languages', $this->languages );
	}

	/**
	 * A single language config.
	 *
	 * @param string $code Language code.
	 * @return array
	 */
	public function language( $code ) {
		$languages = $this->languages();
		return isset( $languages[ $code ] ) ? $languages[ $code ] : reset( $languages );
	}

	/**
	 * Register hooks.
	 */
	public function boot() {
		add_filter( 'locale', array( $this, 'filter_locale' ) );
		add_filter( 'language_attributes', array( $this, 'filter_language_attributes' ) );
		add_filter( 'body_class', array( $this, 'filter_body_class' ) );
		add_action( 'init', array( $this, 'persist_choice' ) );
		add_action( 'wp_head', array( $this, 'output_hreflang' ), 5 );
		add_filter( 'abidar_current_dir', array( $this, 'current_dir' ) );
	}

	/**
	 * Resolve and cache the language for the request.
	 *
	 * @return string
	 */
	public function current_language() {
		if ( $this->current ) {
			return $this->current;
		}

		// 1. External plugins win, so the theme never fights WPML/Polylang.
		$plugin_lang = $this->plugin_language();
		if ( $plugin_lang ) {
			$this->current = isset( $this->languages[ $plugin_lang ] ) ? $plugin_lang : $this->languages[ $plugin_lang ] ?? '';
		}

		// 2. Explicit query argument.
		if ( ! $this->current && isset( $_GET['lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$requested = sanitize_key( wp_unslash( $_GET['lang'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			if ( isset( $this->languages[ $requested ] ) ) {
				$this->current = $requested;
			}
		}

		// 3. Cookie set by a previous visit.
		if ( ! $this->current && isset( $_COOKIE['abidar_lang'] ) ) {
			$cookie = sanitize_key( wp_unslash( $_COOKIE['abidar_lang'] ) );
			if ( isset( $this->languages[ $cookie ] ) ) {
				$this->current = $cookie;
			}
		}

		// 4. Browser preference.
		if ( ! $this->current && ! is_admin() && isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
			$browser = substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ), 0, 4 );
			$browser = strtolower( substr( $browser, 0, 2 ) );
			if ( isset( $this->languages[ $browser ] ) ) {
				$this->current = $browser;
			}
		}

		// 5. Theme default.
		if ( ! $this->current ) {
			$default = abidar_option( 'default_language', 'fa' );
			$this->current = isset( $this->languages[ $default ] ) ? $default : 'fa';
		}

		return apply_filters( 'abidar_current_language', $this->current );
	}

	/**
	 * Detect a language from WPML / Polylang when present.
	 *
	 * @return string
	 */
	protected function plugin_language() {
		if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE ) {
			return strtolower( ICL_LANGUAGE_CODE );
		}

		if ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language( 'slug' );
			if ( $lang ) {
				return strtolower( substr( $lang, 0, 2 ) );
			}
		}

		return '';
	}

	/**
	 * Persist the visitor choice into a cookie.
	 */
	public function persist_choice() {
		if ( is_admin() || headers_sent() ) {
			return;
		}
		if ( ! isset( $_GET['lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		$requested = sanitize_key( wp_unslash( $_GET['lang'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( ! isset( $this->languages[ $requested ] ) ) {
			return;
		}

		setcookie(
			'abidar_lang',
			$requested,
			array(
				'expires'  => time() + YEAR_IN_SECONDS,
				'path'     => COOKIEPATH ? COOKIEPATH : '/',
				'domain'   => COOKIE_DOMAIN,
				'secure'   => is_ssl(),
				'httponly' => false,
				'samesite' => 'Lax',
			)
		);
	}

	/**
	 * Swap the WordPress locale.
	 *
	 * @param string $locale Current locale.
	 * @return string
	 */
	public function filter_locale( $locale ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $locale;
		}
		$lang = $this->current_language();
		return isset( $this->languages[ $lang ] ) ? $this->languages[ $lang ]['locale'] : $locale;
	}

	/**
	 * html lang + dir attribute.
	 *
	 * @param string $output Default attributes.
	 * @return string
	 */
	public function filter_language_attributes( $output ) {
		$lang = $this->language( $this->current_language() );
		$out  = array();

		$out[] = 'lang="' . esc_attr( $lang['html'] ) . '"';
		$out[] = 'dir="' . esc_attr( $lang['dir'] ) . '"';

		return implode( ' ', $out );
	}

	/**
	 * Extra body classes.
	 *
	 * @param array $classes Classes.
	 * @return array
	 */
	public function filter_body_class( $classes ) {
		$lang = $this->language( $this->current_language() );
		$classes[] = 'ab-dir-' . $lang['dir'];
		$classes[] = 'ab-locale-' . $lang['code'];
		return $classes;
	}

	/**
	 * Text direction for the active language.
	 *
	 * @return string
	 */
	public function current_dir() {
		$lang = $this->language( $this->current_language() );
		return $lang['dir'];
	}

	/**
	 * Current language is RTL?
	 *
	 * @return bool
	 */
	public function is_rtl() {
		return 'rtl' === $this->current_dir();
	}

	/**
	 * Build a URL for a given language, preserving the current path & query.
	 *
	 * @param string $code    Target language code.
	 * @param string $url     Optional base URL.
	 * @return string
	 */
	public function language_url( $code, $url = '' ) {
		if ( ! isset( $this->languages[ $code ] ) ) {
			return $url ? $url : home_url( '/' );
		}

		if ( ! $url ) {
			$url = home_url( add_query_arg( array() ) );
		}

		// Strip any existing lang argument.
		$url = remove_query_arg( 'lang', $url );
		$url = add_query_arg( 'lang', $code, $url );

		return apply_filters( 'abidar_language_url', $url, $code );
	}

	/**
	 * Markup for the language switcher.
	 *
	 * @param array $args Args: style (inline|dropdown|list), class.
	 * @return string
	 */
	public function switcher( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'style'     => 'inline',
				'class'     => '',
				'show_flags'=> false,
			)
		);

		$current = $this->current_language();
		$out     = '<div class="ab-lang ab-lang--' . esc_attr( $args['style'] ) . ' ' . esc_attr( $args['class'] ) . '">';

		if ( 'dropdown' === $args['style'] ) {
			$out .= '<button type="button" class="ab-lang__toggle" aria-expanded="false" aria-haspopup="true">';
			$out .= '<span class="ab-lang__globe" aria-hidden="true">' . abidar_icon( 'globe', false ) . '</span>';
			$out .= '<span>' . esc_html( $this->languages[ $current ]['short'] ) . '</span>';
			$out .= abidar_icon( 'chevron-down', false );
			$out .= '</button><ul class="ab-lang__menu">';
		} else {
			$out .= '<ul class="ab-lang__menu">';
		}

		foreach ( $this->languages() as $code => $lang ) {
			$out .= sprintf(
				'<li class="ab-lang__item%1$s"><a href="%2$s" hreflang="%3$s" lang="%3$s" data-lang="%4$s"%5$s>',
				$code === $current ? ' is-current' : '',
				esc_url( $this->language_url( $code ) ),
				esc_attr( $lang['html'] ),
				esc_attr( $code ),
				$code === $current ? ' aria-current="true"' : ''
			);
			if ( $args['show_flags'] ) {
				$out .= '<span class="ab-lang__flag" aria-hidden="true">' . esc_html( $lang['flag'] ) . '</span>';
			}
			$out .= '<span class="ab-lang__label">' . esc_html( 'inline' === $args['style'] ? $lang['short'] : $lang['label'] ) . '</span>';
			$out .= '</a></li>';
		}

		$out .= '</ul>';
		if ( 'dropdown' === $args['style'] ) {
			// toggle closed.
		}
		$out .= '</div>';

		return $out;
	}

	/**
	 * hreflang alternates for SEO.
	 */
	public function output_hreflang() {
		if ( is_admin() ) {
			return;
		}
		foreach ( $this->languages() as $code => $lang ) {
			printf(
				'<link rel="alternate" hreflang="%1$s" href="%2$s">' . "\n",
				esc_attr( $lang['html'] ),
				esc_url( $this->language_url( $code ) )
			);
		}
	}
}
