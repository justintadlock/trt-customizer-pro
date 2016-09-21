<?php
//Following Justin Tadlock example 1, the following is a proposition of implementation using the javascript customizer api.
//It basically does the same task of example 1 : adds a Pro theme section in the base panel of the customizer.
//Feel free to comment and ping me on github and WordPress.org @nikeo
//Thanks @justintadlock for initializing this repo and opening the discussion.

//How to use?
//1) Add this code to your functions.php. You can also create a separate file and include it in your theme.
//2) Replace the example values with yours

//'customize_controls_init' fires when Customizer controls are initialized, before scripts are enqueued.
//@see wp-admin/customize.php#53
add_action( 'customize_controls_init', 'add_pro_section' );
function add_pro_section() {
    new Example_2_js_customize(
        //Example values
        array(
          'type' => 'example-2-js',//this is the
          'title' => esc_html__( 'Theme Name Pro', 'example-2-js' ),
          'pro_text' => esc_html__( 'Go Pro', 'example-2-js' ),
          'pro_url' => 'http://example.com'
        )
    );
}

class Example_2_js_customize {
    public $type;
    public $title;
    public $pro_text;
    public $pro_url;

    //@params = array() of user params
    //@params is normalized with defaults params
    function __construct( $params = array() ) {
        $params = wp_parse_args(
            $params,
            array(
              'type' => 'example-2-js',//this is the section identifier
              'title' => esc_html__( 'Theme Name Pro', 'example-2-js' ),
              'pro_text' => esc_html__( 'Go Pro', 'example-2-js' ),
              'pro_url' => 'http://example.com'
            )
        );

        $keys = array_keys( get_object_vars( $this ) );
        foreach ( $keys as $key ) {
          if ( isset( $params[ $key ] ) ) {
            $this->$key = $params[ $key ];
          }
        }

        //'customize_controls_print_scripts' is fired in wp-admin/customize.php, in the <head> tag of the customizer pane
        add_action( 'customize_controls_print_scripts', array( $this, 'print_pro_section' ) );
    }


    //hook : customize_controls_print_script
    function print_pro_section() {
      /*************************************/
      //Embed the pro section
      //Uses the with the customizer js api
      /*************************************/
      ?>
      <script type="text/javascript" id="pro-section-script">
        //let's fire those actions on DOM ready
        //Not mandatory, but we're sure that at this time the wp.customize object exists
        jQuery( function() {
              var api = api || wp.customize,
                  _type = '<?php echo $this -> type; ?>',
                  _title = '<?php echo $this -> title; ?>',
                  _pro_text = '<?php echo $this -> pro_text; ?>',
                  _pro_url = '<?php echo $this -> pro_url; ?>',
                  _addProSection = function() {
                      var proSectionConstructor = api.Section.extend( {
                          // No events for this type of section.
                          attachEvents: function () {},
                          // Always make the section active.
                          isContextuallyActive: function () {
                            return this.active();
                          },
                          _toggleActive: function(){ return true; },
                      } );
                      api.section.add( _type, new proSectionConstructor( _type, {
                          params : {
                              active : true,
                              id : _type,
                              type : _type,
                              title : _title,
                              'pro_text' : _pro_text,
                              'pro_url'  : _pro_url
                          }
                      } ) );
              };//_addProSection
              //makes sure the Pro section is added on api ready
              if ( api.topics && api.topics.ready && api.topics.ready.fired() )
                _addProSection();
              else
                api.bind('ready', _addProSection );
        });
      </script>
      <?php

      /*************************************/
      //prints the pro section template
      //the identifier is $this -> type
      /*************************************/
      ?>
        <script type="text/html" id="tmpl-customize-section-<?php echo $this -> type; ?>">
          <li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
            <h3 class="accordion-section-title">
              {{ data.title }}
              <# if ( data.pro_text && data.pro_url ) { #>
                <a href="{{ data.pro_url }}" class="button button-secondary alignright" target="_blank">{{ data.pro_text }}</a>
              <# } #>
            </h3>
          </li>
        </script>
      <?php


      /*************************************/
      //prints the pro section style
      //the identifier is $this -> type
      /*************************************/
      ?>
      <style type="text/css">
        #customize-controls .control-section-<?php echo $this -> type ?> .accordion-section-title:hover,
        #customize-controls .control-section-<?php echo $this -> type ?> .accordion-section-title:focus {
          background-color: #fff;
        }
        #customize-theme-controls .control-section-<?php echo $this -> type ?> .accordion-section-title:after {
          content: none;
        }
        .control-section-<?php echo $this -> type ?> .accordion-section-title .button {
          margin-top:  -4px;
          font-weight: 400;
          margin-left: 8px;
        }
        .rtl .control-section-<?php echo $this -> type ?> .accordion-section-title .button {
          margin-left:  0;
          margin-right: 8px;
        }
      </style>
      <?php
    }//print_pro_section()
}//class