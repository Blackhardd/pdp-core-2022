<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class PDP_Core_CPT {
    public function init_post_types(){
        $this->register_salon();
        $this->register_salon_taxonomies();
        $this->register_promotion();
        $this->register_vacancy();
        $this->register_testimonial();
        $this->register_banner();
    }

    public function init_post_types_meta(){
    	$this->register_city_taxonomy_meta();
        $this->register_salon_meta();
        $this->register_promotion_meta();
        $this->register_vacancy_meta();
        $this->register_testimonial_meta();
        $this->register_banner_meta();
    }

    private function register_salon(){
        register_post_type( 'salon', array(
            'labels'                => array(
                'name'                  => __( 'Салоны', 'pdp_core' ),
                'singular_name'         => __( 'Салон', 'pdp_core' ),
                'add_new'               => __( 'Добавить новый', 'pdp_core' ),
                'add_new_item'          => __( 'Добавить новый салон', 'pdp_core' ),
                'edit_item'             => __( 'Редактировать салон', 'pdp_core' ),
                'new_item'              => __( 'Новый салон', 'pdp_core' ),
                'view_item'             => __( 'Посмотреть салон', 'pdp_core' ),
                'search_items'          => __( 'Найти салон', 'pdp_core' ),
                'not_found'             => __( 'Салонов не найдено', 'pdp_core' ),
                'not_found_in_trash'    => __( 'В корзине салонов не найдено', 'pdp_core' ),
                'menu_name'             => __( 'Салоны', 'pdp_core' )
            ),
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 4,
            'menu_icon'             => 'none',
            'supports'              => array( 'title', 'editor', 'thumbnail' )
        ) );
    }

    private function register_salon_taxonomies(){
        register_taxonomy( 'city', array( 'salon' ), array(
            'hierarchical'  => true,
            'labels'        => array(
                'name'              => __( 'Города', 'pdp_core' ),
                'singular_name'     => __( 'Город', 'pdp_core' ),
                'search_items'      => __( 'Найти город', 'pdp_core' ),
                'all_items'         => __( 'Все города', 'pdp_core' ),
                'parent_item'       => __( 'Родительский город', 'pdp_core' ),
                'parent_item_colon' => __( 'Родительский город:', 'pdp_core' ),
                'edit_item'         => __( 'Редактировать город', 'pdp_core' ),
                'update_item'       => __( 'Обновить город', 'pdp_core' ),
                'add_new_item'      => __( 'Добавить новый город', 'pdp_core' ),
                'new_item_name'     => __( 'Название нового города', 'pdp_core' ),
                'menu_name'         => __( 'Город', 'pdp_core' ),
            ),
            'show_ui'       => true,
            'query_var'     => true
        ) );
    }

    private function register_city_taxonomy_meta(){
	    Container::make( 'term_meta', __( 'Настройки города', 'pdp_core' ) )
	        ->where( 'term_taxonomy', '=', 'city' )
	        ->add_fields( array(
		        Field::make( 'select', 'display_in_header', __( 'Отображать в шапке', 'pdp_core' ) )
		            ->set_options( array(
			            'yes'   => __( 'Да', 'pdp_core' ),
			            'no'    => __( 'Нет', 'pdp_core' ),
		            ) )
		            ->set_default_value( 'yes' )
	            ) );
    }

    private function register_salon_meta(){
        Container::make( 'post_meta', __( 'Настройки салона', 'pdp' ) )
            ->where( 'post_type', '=', 'salon' )
            ->add_tab( __( 'Основная информация', 'pdp' ), array(
            	Field::make( 'text', 'order_position', __( 'Позиция в списках', 'pdp' ) ),
	            Field::make( 'text', 'title', __( 'Заголовок', 'pdp' ) ),
	            Field::make( 'text', 'outer_link', __( 'Внешняя ссылка', 'pdp' ) ),
	            Field::make( 'text', 'instagram', __( 'Instagram', 'pdp' ) ),
                Field::make( 'text', 'email', __( 'Электронная почта', 'pdp' ) )
                    ->set_attribute( 'type', 'email' )
                    ->set_width( 50 ),
                Field::make( 'text', 'phone', __( 'Номер телефона', 'pdp' ) )
                    ->set_attribute( 'type', 'tel' )
                    ->set_width( 50 ),
	            Field::make( 'text', 'map_link', __( 'Ссылка на Google Maps', 'pdp' ) )
	                 ->set_width( 50 ),
	            Field::make( 'text', 'latitude', __( 'Широта', 'pdp' ) )
	                 ->set_width( 25 ),
	            Field::make( 'text', 'longitude', __( 'Долгота', 'pdp' ) )
	                 ->set_width( 25 ),
                Field::make( 'text', 'pricelist_sheet_id', __( 'ID таблицы прайслиста', 'pdp' ) )
                    ->set_width( 100 ),
	            Field::make( 'textarea', 'notification_recipients', __( 'Email получателей уведомлений (через запятую)', 'pdp' ) ),
	            Field::make( 'image', 'cover1x', __( 'Обложка (1x)', 'pdp' ) ),
	            Field::make( 'image', 'cover2x', __( 'Обложка (2x)', 'pdp' ) )
            ) )
	        ->add_tab( __( 'Галерея', 'pdp' ), array(
				Field::make( 'complex', 'slider_gallery', __( 'Слайды', 'pdp' ) )
		            ->add_fields( array(
						Field::make( 'image', 'image1x', __( 'Изображение (1x)' ) )
		                    ->set_width( 50 ),
						Field::make( 'image', 'image2x', __( 'Изображение (2x)' ) )
							->set_width( 50 )
		            ) )
	        ) )
            ->add_tab( __( 'Отображение салона', 'pdp' ), array(
            	Field::make( 'select', 'display_in_booking', __( 'Отображать на странице записи', 'pdp' ) )
	                ->set_options( array(
	                	'yes'   => __( 'Да', 'pdp' ),
	                	'no'    => __( 'Нет', 'pdp' ),
	                ) )
	                ->set_default_value( 'yes' )
	                ->set_width( 30 ),
	            Field::make( 'select', 'display_in_header', __( 'Отображать в шапке', 'pdp' ) )
	                 ->set_options( array(
		                 'yes'   => __( 'Да', 'pdp' ),
		                 'no'    => __( 'Нет', 'pdp' ),
	                 ) )
	                 ->set_default_value( 'yes' )
	                 ->set_width( 30 ),
	            Field::make( 'select', 'display_in_forms', __( 'Отображать в формах', 'pdp' ) )
	                 ->set_options( array(
		                 'yes'   => __( 'Да', 'pdp' ),
		                 'no'    => __( 'Нет', 'pdp' ),
	                 ) )
	                 ->set_default_value( 'yes' )
	                 ->set_width( 30 )
            ) );
    }


    /**
     * Promotions
     */
    private function register_promotion(){
        register_post_type( 'promotion', array(
            'labels'                => array(
                'name'                  => __( 'Акции', 'pdp_core' ),
                'singular_name'         => __( 'Акция', 'pdp_core' ),
                'add_new'               => __( 'Добавить новую', 'pdp_core' ),
                'add_new_item'          => __( 'Добавить новую акцию', 'pdp_core' ),
                'edit_item'             => __( 'Редактировать акцию', 'pdp_core' ),
                'new_item'              => __( 'Новая акция', 'pdp_core' ),
                'view_item'             => __( 'Посмотреть акцию', 'pdp_core' ),
                'search_items'          => __( 'Найти акцию', 'pdp_core' ),
                'not_found'             => __( 'Акций не найдено', 'pdp_core' ),
                'not_found_in_trash'    => __( 'В корзине акций не найдено', 'pdp_core' ),
                'menu_name'             => __( 'Акции', 'pdp_core' )
            ),
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'none',
            'supports'              => array( 'title', 'editor', 'thumbnail' )
        ) );
    }

	private function register_promotion_meta(){
		Container::make( 'post_meta', __( 'Настройки акции', 'pdp_core' ) )
			->where( 'post_type', '=', 'promotion' )
			->add_fields( array(
				Field::make( 'radio', 'type', __( 'Тип акции', 'pdp_core' ) )
					->set_options( array(
						'permanent'     => __( 'Постоянная', 'pdp_core' ),
						'temporary' => __( 'Временная', 'pdp_core' )
					) ),
				Field::make( 'date', 'start_date', __( 'Дата начала', 'pdp_core' ) )
					->set_width( 50 )
					->set_conditional_logic( array(
						'relation' => 'AND',
						array(
							'field'     => 'type',
							'value'     => 'temporary',
							'compare'   => '='
						)
					) ),
				Field::make( 'date', 'end_date', __( 'Дата конца', 'pdp_core' ) )
					->set_width( 50 )
					->set_conditional_logic( array(
						'relation' => 'AND',
						array(
							'field'     => 'type',
							'value'     => 'temporary',
							'compare'   => '='
						)
					) )
			) );
	}


    /**
     * Masters
     */
    private function register_master(){
        register_post_type( 'master', array(
            'labels'                => array(
                'name'                  => __( 'Мастера', 'pdp_core' ),
                'singular_name'         => __( 'Мастер', 'pdp_core' ),
                'add_new'               => __( 'Добавить нового', 'pdp_core' ),
                'add_new_item'          => __( 'Добавить нового мастера', 'pdp_core' ),
                'edit_item'             => __( 'Редактировать мастера', 'pdp_core' ),
                'new_item'              => __( 'Новый мастер', 'pdp_core' ),
                'view_item'             => __( 'Посмотреть мастера', 'pdp_core' ),
                'search_items'          => __( 'Найти мастера', 'pdp_core' ),
                'not_found'             => __( 'Мастера не найдено', 'pdp_core' ),
                'not_found_in_trash'    => __( 'В корзине мастера не найдено', 'pdp_core' ),
                'menu_name'             => __( 'Мастера', 'pdp_core' )
            ),
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 6,
            'menu_icon'             => 'none',
            'supports'              => array( 'title', 'editor', 'thumbnail' )
        ) );
    }

    private function register_master_taxonomies(){
	    register_taxonomy( 'salon', array( 'master' ), array(
		    'hierarchical'  => true,
		    'labels'        => array(
			    'name'              => __( 'Салоны', 'pdp_core' ),
			    'singular_name'     => __( 'Салон', 'pdp_core' ),
			    'search_items'      => __( 'Найти салон', 'pdp_core' ),
			    'all_items'         => __( 'Все салоны', 'pdp_core' ),
			    'parent_item'       => __( 'Родительский салон', 'pdp_core' ),
			    'parent_item_colon' => __( 'Родительский салон:', 'pdp_core' ),
			    'edit_item'         => __( 'Редактировать салон', 'pdp_core' ),
			    'update_item'       => __( 'Обновить салон', 'pdp_core' ),
			    'add_new_item'      => __( 'Добавить новый салон', 'pdp_core' ),
			    'new_item_name'     => __( 'Название нового салона', 'pdp_core' ),
			    'menu_name'         => __( 'Салон', 'pdp_core' ),
		    ),
		    'show_ui'           => true,
		    'query_var'         => true,
		    'show_admin_column' => true,
	    ) );
    }

    private function register_master_meta(){
        Container::make( 'post_meta', __( 'Настройки мастера', 'pdp_core' ) )
            ->where( 'post_type', '=', 'master' )
            ->add_fields( array(
                Field::make( 'text', 'specialty', __( 'Специальность', 'pdp_core' ) ),
                Field::make( 'text', 'experience', __( 'Опыт работы', 'pdp_core' ) )
            ) );
    }


    /**
     * Vacancies
     */

    private function register_vacancy(){
        register_post_type( 'vacancy', array(
            'labels'                => array(
                'name'                  => __( 'Вакансии', 'pdp_core' ),
                'singular_name'         => __( 'Вакансия', 'pdp_core' ),
                'add_new'               => __( 'Добавить новую', 'pdp_core' ),
                'add_new_item'          => __( 'Добавить новую вакансию', 'pdp_core' ),
                'edit_item'             => __( 'Редактировать вакансию', 'pdp_core' ),
                'new_item'              => __( 'Новая вакансия', 'pdp_core' ),
                'view_item'             => __( 'Посмотреть вакансию', 'pdp_core' ),
                'search_items'          => __( 'Найти вакансию', 'pdp_core' ),
                'not_found'             => __( 'Вакансий не найдено', 'pdp_core' ),
                'not_found_in_trash'    => __( 'В корзине вакансий не найдено', 'pdp_core' ),
                'menu_name'             => __( 'Вакансии', 'pdp_core' )
            ),
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 6,
            'menu_icon'             => 'none',
            'supports'              => array( 'title', 'editor' )
        ) );
    }

    private function register_vacancy_meta(){
	    Container::make( 'post_meta', __( 'Настройки вакансии', 'pdp' ) )
	        ->where( 'post_type', '=', 'vacancy' )
	        ->add_fields( array(
	        	Field::make( 'radio', 'actual', __( 'Актуальность', 'pdp' ) )
		             ->set_options( array(
		             	'true'      => __( 'Актуально', 'pdp' ),
		             	'false'     => __( 'Не актуально', 'pdp' )
		             ) )
	        ) );
    }


    /**
     *  Testimonials
     */

    private function register_testimonial(){
	    register_post_type( 'testimonial', array(
		    'labels'                => array(
			    'name'                  => __( 'Отзывы', 'pdp' ),
			    'singular_name'         => __( 'Отзыв', 'pdp' ),
			    'add_new'               => __( 'Добавить новый', 'pdp' ),
			    'add_new_item'          => __( 'Добавить новый отзыв', 'pdp' ),
			    'edit_item'             => __( 'Редактировать отзыв', 'pdp' ),
			    'new_item'              => __( 'Новый отзыв', 'pdp' ),
			    'view_item'             => __( 'Посмотреть отзыв', 'pdp' ),
			    'search_items'          => __( 'Найти отзыв', 'pdp' ),
			    'not_found'             => __( 'Отзывы не найдены', 'pdp' ),
			    'not_found_in_trash'    => __( 'В корзине отзывов не найдено', 'pdp' ),
			    'menu_name'             => __( 'Отзывы', 'pdp' )
		    ),
		    'public'                => true,
		    'publicly_queryable'    => true,
		    'show_ui'               => true,
		    'show_in_menu'          => true,
		    'query_var'             => true,
		    'rewrite'               => true,
		    'capability_type'       => 'post',
		    'has_archive'           => true,
		    'hierarchical'          => false,
		    'menu_position'         => 6,
		    'menu_icon'             => 'none',
		    'supports'              => array( 'title', 'editor' )
	    ) );
    }

	private function register_testimonial_meta(){
		Container::make( 'post_meta', __( 'Настройки отзыва', 'pdp_core' ) )
			->where( 'post_type', '=', 'testimonial' )
			->add_fields( array(
				Field::make( 'text', 'occupation', __( 'Род деятельности', 'pdp' ) ),
				Field::make( 'image', 'small1x', __( 'Маленькое фото (1x)', 'pdp' ) ),
				Field::make( 'image', 'small2x', __( 'Маленькое фото (2x)', 'pdp' ) ),
				Field::make( 'image', 'large1x', __( 'Большое фото (1x)', 'pdp' ) ),
				Field::make( 'image', 'large2x', __( 'Большое фото (2x)', 'pdp' ) )
			) );
	}


	/**
	 *  Banners
	 */

	private function register_banner(){
		register_post_type( 'banner', array(
			'labels'                => array(
				'name'                  => __( 'Баннеры', 'pdp' ),
				'singular_name'         => __( 'Баннер', 'pdp' ),
				'add_new'               => __( 'Добавить новый', 'pdp' ),
				'add_new_item'          => __( 'Добавить новый баннер', 'pdp' ),
				'edit_item'             => __( 'Редактировать баннер', 'pdp' ),
				'new_item'              => __( 'Новый баннер', 'pdp' ),
				'view_item'             => __( 'Посмотреть баннер', 'pdp' ),
				'search_items'          => __( 'Найти баннер', 'pdp' ),
				'not_found'             => __( 'Баннеры не найдены', 'pdp' ),
				'not_found_in_trash'    => __( 'В корзине баннеров не найдено', 'pdp' ),
				'menu_name'             => __( 'Баннеры', 'pdp' )
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => 6,
			'menu_icon'             => 'none',
			'supports'              => array( 'title', 'editor' )
		) );
	}

	private function register_banner_meta(){
		Container::make( 'post_meta', __( 'Настройки баннера', 'pdp' ) )
			->where( 'post_type', '=', 'banner' )
			->add_fields( array(
				Field::make( 'text', 'button', __( 'Текст кнопки', 'pdp' ) ),
				Field::make( 'text', 'link', __( 'Ссылка', 'pdp' ) ),
				Field::make( 'select', 'banner_type', __( 'Тип', 'pdp' ) )
					->add_options( array(
						'image' => __( 'Картинка', 'pdp' ),
						'video' => __( 'Видео', 'pdp' )
					) ),
				Field::make( 'image', 'video_mp4', __( 'Видео (MP4)', 'pdp' ) )
				     ->set_conditional_logic( array(
						array(
							'field' => 'banner_type',
							'value' => 'video'
						)
				     ) )
					->set_type( array( 'video' ) ),
				Field::make( 'image', 'image1x', __( 'Фон (1x)', 'pdp' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'banner_type',
							'value' => 'image'
						)
					) ),
				Field::make( 'image', 'image2x', __( 'Фон (2x)', 'pdp' ) )
					->set_conditional_logic( array(
						array(
							'field' => 'banner_type',
							'value' => 'image'
						)
					) )
			) );
	}
}