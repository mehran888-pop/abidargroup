<?php
/**
 * Service data used by the front page, the Services widget and the demo importer.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'icon'  => 'mountain',
		'image' => 'hero-mine.jpg',
		'title' => array(
			'fa' => 'معدن و اکتشاف',
			'en' => 'Mining & Exploration',
			'zh' => '采矿与勘探',
		),
		'text'  => array(
			'fa' => 'اکتشاف، مدل‌سازی زمین‌شناسی، طراحی پیت و بهره‌برداری بهینه با ناوگان اختصاصی و تیم زمین‌شناسی مجرب.',
			'en' => 'Exploration, geological modelling, pit design and optimised extraction with our own fleet and an experienced geology team.',
			'zh' => '勘探、地质建模、露天矿设计与高效开采，配备自有设备队伍与资深地质团队。',
		),
		'url'   => '/services/#mining',
		'items' => array(
			array( 'fa' => 'اکتشاف و نمونه‌برداری', 'en' => 'Exploration & sampling', 'zh' => '勘探与取样' ),
			array( 'fa' => 'طراحی پیت و برنامه‌ریزی', 'en' => 'Pit design & scheduling', 'zh' => '采场设计与排程' ),
			array( 'fa' => 'عملیات استخراج و حمل', 'en' => 'Extraction & haulage', 'zh' => '开采与运输' ),
		),
	),
	array(
		'icon'  => 'oil',
		'image' => 'oil-gas.jpg',
		'title' => array(
			'fa' => 'نفت و گاز',
			'en' => 'Oil & Gas',
			'zh' => '石油与天然气',
		),
		'text'  => array(
			'fa' => 'خدمات بالادستی و میان‌دستی، احداث و تعمیر خط لوله، نگهداری تأسیسات و بازرسی فنی با مجوزهای معتبر.',
			'en' => 'Upstream and midstream services, pipeline construction and repair, facility maintenance and technical inspection.',
			'zh' => '上游与中游服务、管道建设与维修、设施维护及技术检测。',
		),
		'url'   => '/services/#oil-gas',
		'items' => array(
			array( 'fa' => 'احداث خط لوله', 'en' => 'Pipeline construction', 'zh' => '管道建设' ),
			array( 'fa' => 'نگهداری و تعمیرات', 'en' => 'Maintenance & repair', 'zh' => '维护与检修' ),
			array( 'fa' => 'بازرسی فنی و HSE', 'en' => 'Inspection & HSE', 'zh' => '检测与 HSE' ),
		),
	),
	array(
		'icon'  => 'cube',
		'image' => 'stone.jpg',
		'title' => array(
			'fa' => 'سنگ طبیعی',
			'en' => 'Natural Stone',
			'zh' => '天然石材',
		),
		'text'  => array(
			'fa' => 'استخراج، فرآوری و صادرات مرمر، تراورتن و سنگ آهکی با کیفیت صادراتی و بسته‌بندی استاندارد.',
			'en' => 'Quarrying, processing and export of marble, travertine and limestone at export grade with standard packaging.',
			'zh' => '大理石、洞石与石灰石的开采、加工与出口，达到出口级品质与标准包装。',
		),
		'url'   => '/services/#stone',
		'items' => array(
			array( 'fa' => 'استخراج بلوک', 'en' => 'Block quarrying', 'zh' => '荒料开采' ),
			array( 'fa' => 'برش و پولیش اسلب', 'en' => 'Slab cutting & polishing', 'zh' => '板材切割与抛光' ),
			array( 'fa' => 'صادرات و لجستیک', 'en' => 'Export & logistics', 'zh' => '出口与物流' ),
		),
	),
	array(
		'icon'  => 'ruler',
		'image' => 'project1.jpg',
		'title' => array(
			'fa' => 'مهندسی و مشاوره',
			'en' => 'Engineering & Consultancy',
			'zh' => '工程与咨询',
		),
		'text'  => array(
			'fa' => 'مطالعات امکان‌سنجی، طراحی مفهومی، مدیریت پروژه و پایش HSE در تمام مراحل چرخه عمر پروژه.',
			'en' => 'Feasibility studies, conceptual design, project management and HSE monitoring across the full project life cycle.',
			'zh' => '可行性研究、概念设计、项目管理及全生命周期 HSE 监督。',
		),
		'url'   => '/services/#engineering',
		'items' => array(
			array( 'fa' => 'مطالعات امکان‌سنجی', 'en' => 'Feasibility studies', 'zh' => '可行性研究' ),
			array( 'fa' => 'مدیریت پروژه', 'en' => 'Project management', 'zh' => '项目管理' ),
			array( 'fa' => 'ممیزی فنی و HSE', 'en' => 'Technical & HSE audits', 'zh' => '技术与 HSE 审计' ),
		),
	),
);
