<?php
/**
 * Demo content: page bodies used by the importer and the fallback front page.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block markup for a demo page.
 *
 * Each demo page uses a dedicated page template, but the content below is
 * written in the block editor so clients can take over with Gutenberg without
 * losing the layout.
 *
 * @param string $slug Page slug.
 * @return string
 */
function abidar_demo_page_content( $slug ) {
	$blocks = array(
		'about'    => array(
			'ab-page-title',
			'ab-about',
			'ab-counters',
			'ab-process',
			'ab-team',
			'ab-cta',
		),
		'services' => array(
			'ab-page-title',
			'ab-services',
			'ab-process',
			'ab-accordion',
			'ab-cta',
		),
		'projects' => array(
			'ab-page-title',
			'ab-projects',
			'ab-counters',
			'ab-cta',
		),
		'team'     => array(
			'ab-page-title',
			'ab-team',
			'ab-testimonials',
			'ab-cta',
		),
		'contact'  => array(
			'ab-page-title',
			'ab-contact',
		),
	);

	if ( ! isset( $blocks[ $slug ] ) ) {
		return '';
	}

	$out = '';
	foreach ( $blocks[ $slug ] as $block ) {
		// Every section is also available as an Elementor widget with the same key.
		$out .= '<!-- wp:abidar/section {"section":"' . esc_attr( $block ) . '"} /-->' . "\n";
	}

	return $out;
}

/**
 * Demo blog posts used by the importer.
 *
 * @return array
 */
function abidar_demo_posts() {
	return array(
		array(
			'image'    => 'hero-mine',
			'date'     => gmdate( 'Y-m-d H:i:s', strtotime( '-6 days' ) ),
			'category' => array( 'fa' => 'معدن', 'en' => 'Mining', 'zh' => '采矿' ),
			'title'    => array(
				'fa' => 'چگونه مدل‌سازی زمین‌شناسی بهره‌وری معدن را افزایش می‌دهد',
				'en' => 'How geological modelling lifts mine productivity',
				'zh' => '地质建模如何提升矿山生产率',
			),
			'excerpt'  => array(
				'fa' => 'بازنگری مدل ذخیره با داده‌های حفاری جدید می‌تواند تا ۲۰ درصد بهره‌وری پیت را بهبود دهد.',
				'en' => 'Re-modelling a reserve with fresh drilling data can improve pit productivity by up to 20%.',
				'zh' => '利用最新钻探数据重新建模储量，可将采场生产率提升多达20%。',
			),
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Geological modelling is the foundation of a predictable mine plan.', 'abidar' ) . '</p><!-- /wp:paragraph --><!-- wp:heading --><h2>' . esc_html__( 'Start with clean data', 'abidar' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'Collars, surveys and assay data must be reconciled before any block model is built. In our projects we spend two to three weeks on validation alone.', 'abidar' ) . '</p><!-- /wp:paragraph -->',
		),
		array(
			'image'    => 'project2',
			'date'     => gmdate( 'Y-m-d H:i:s', strtotime( '-19 days' ) ),
			'category' => array( 'fa' => 'نفت و گاز', 'en' => 'Oil & Gas', 'zh' => '石油与天然气' ),
			'title'    => array(
				'fa' => 'ایمنی در پروژه‌های خط لوله: سه درس از میدان',
				'en' => 'Pipeline safety: three lessons from the field',
				'zh' => '管道安全：来自现场的三大经验',
			),
			'excerpt'  => array(
				'fa' => 'برنامه‌ریزی HSE نباید سند باشد؛ باید رفتار روزانه تیم را شکل دهد.',
				'en' => 'An HSE plan should not be a document — it should shape daily team behaviour.',
				'zh' => 'HSE 方案不应只是一份文件，而应塑造团队的日常行为。',
			),
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Safety performance is a leading indicator of project performance.', 'abidar' ) . '</p><!-- /wp:paragraph --><!-- wp:list --><ul><li>' . esc_html__( 'Pre-task briefings every shift', 'abidar' ) . '</li><li>' . esc_html__( 'Empowered stop-work authority', 'abidar' ) . '</li><li>' . esc_html__( 'Weekly audits with published results', 'abidar' ) . '</li></ul><!-- /wp:list -->',
		),
		array(
			'image'    => 'stone',
			'date'     => gmdate( 'Y-m-d H:i:s', strtotime( '-34 days' ) ),
			'category' => array( 'fa' => 'سنگ', 'en' => 'Stone', 'zh' => '石材' ),
			'title'    => array(
				'fa' => 'راهنمای انتخاب سنگ طبیعی برای پروژه‌های صادراتی',
				'en' => 'Choosing natural stone for export projects',
				'zh' => '出口项目天然石材选择指南',
			),
			'excerpt'  => array(
				'fa' => 'از جذب آب تا ثبات رنگ؛ پنج معیاری که خریداران حرفه‌ای بررسی می‌کنند.',
				'en' => 'From water absorption to colour consistency — the five criteria professional buyers check.',
				'zh' => '从吸水率到颜色一致性——专业买家关注的五项指标。',
			),
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Buyers judge stone long before it reaches the site.', 'abidar' ) . '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>' . esc_html__( 'Consistency between blocks matters more than the absolute quality of a single slab.', 'abidar' ) . '</p><!-- /wp:paragraph -->',
		),
	);
}
