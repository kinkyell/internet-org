<?php

class IORG_CEI_Exporter {

	private $request;
	private $parser;

	public function run( $request ) {

		$this->request = $request;

		if ( isset( $this->request['site'] ) ) {
			switch_to_blog( $this->request['site'] );
			$theme = wp_get_theme();
			require_once $theme->get_template_directory() . '/functions.php';
		}

		$this->parser = new IORG_CEI_Shortcode_Parser;

		if ( isset( $this->request['ids'] ) ) {
			$ids = explode( ',', $this->request['ids'] );
			$this->ids( $ids );
		}

	}

	private function ids( $ids ) {

		$args = array(
			'post__in'  	 => $ids,
			'post_type' 	 => 'any',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			echo '<wp-obj wp_post_id="' . $post->ID . '" wp_post_title="' . esc_attr( $post->post_title ) . '">';
			echo $this->parser->to_xml( $post->post_content );
			echo '</wp-obj>';
		}
	}

	private function query() {

	}

}
