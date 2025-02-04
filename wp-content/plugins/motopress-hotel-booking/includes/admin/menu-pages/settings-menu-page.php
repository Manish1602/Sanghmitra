<?php

namespace MPHB\Admin\MenuPages;

use \MPHB\Admin\Fields;
use \MPHB\Admin\Groups;
use \MPHB\Admin\Tabs;
use \MPHB\Utils\ThirdPartyPluginsUtils;

class SettingsMenuPage extends AbstractMenuPage {

	/**
	 *
	 * @var Tabs\SettingsTab[]
	 */
	protected $tabs = array();

	public function initFields(){

		$generalTab				 = $this->_generateGeneralTab();
		$adminEmailsTab			 = $this->_generateAdminEmailsTab();
		$customerEmailsTab		 = $this->_generateCustomerEmailsTab();
		$globalEmailSettingsTab	 = $this->_generateGlobalEmailSettingsTab();
		$paymentsTab			 = $this->_generatePaymentsTab();
		$extensionsTab			 = $this->_generateExtensionsTab();

		$this->tabs = array(
			$generalTab->getName()				 => $generalTab,
			$adminEmailsTab->getName()			 => $adminEmailsTab,
			$customerEmailsTab->getName()		 => $customerEmailsTab,
			$globalEmailSettingsTab->getName()	 => $globalEmailSettingsTab,
			$paymentsTab->getName()				 => $paymentsTab,
			$extensionsTab->getName()			 => $extensionsTab
		);

		if ( MPHB()->settings()->license()->isEnabled() ) {
			$licenseTab							 = $this->_generateLicenseTab();
			$this->tabs[$licenseTab->getName()]	 = $licenseTab;
		}
	}

	/**
	 *
	 * @return Tabs\SettingsTab
	 */
	private function _generateGeneralTab(){
		$generalTab = new Tabs\SettingsTab( 'general', __( 'General', 'motopress-hotel-booking' ), $this->name );

		// Pages
		$pagesGroup = new Groups\SettingsGroup( 'mphb_pages', __( 'Pages', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );

		$pagesGroupFields = array(
			Fields\FieldFactory::create( 'mphb_search_results_page', array(
				'type'			 => 'page-select',
				'label'			 => __( 'Search Results Page', 'motopress-hotel-booking' ),
				'description'	 => __( 'Select page to display search results. Use search results shortcode on this page.', 'motopress-hotel-booking' ),
				'default'		 => ''
			) ),
			Fields\FieldFactory::create( 'mphb_checkout_page', array(
				'type'			 => 'page-select',
				'label'			 => __( 'Checkout Page', 'motopress-hotel-booking' ),
				'description'	 => __( 'Select page user will be redirected to complete booking.', 'motopress-hotel-booking' ),
				'default'		 => ''
			) ),
			Fields\FieldFactory::create( 'mphb_terms_and_conditions_page', array(
				'type'			 => 'page-select',
				'label'			 => __( 'Terms & Conditions', 'motopress-hotel-booking' ),
				'description'	 => __( 'If you define a "Terms" page the customer will be asked if they accept them when checking out.', 'motopress-hotel-booking' ),
				'default'		 => ''
			) )
		);

        $this->filterGroupFields($pagesGroupFields, $pagesGroup->getName());
		$pagesGroup->addFields( $pagesGroupFields );

		// Misc
		$miscGroup = new Groups\SettingsGroup( 'mphb_misc', __( 'Misc', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );

		$miscGroupFields = array(
			Fields\FieldFactory::create( 'mphb_square_unit', array(
				'type'		 => 'select',
				'label'		 => __( 'Square Units', 'motopress-hotel-booking' ),
				'list'		 => MPHB()->settings()->units()->getBundle()->getLabels(),
				'default'	 => 'm2'
			) ),
			Fields\FieldFactory::create( 'mphb_currency_symbol', array(
				'type'		 => 'select',
				'label'		 => __( 'Currency', 'motopress-hotel-booking' ),
				'list'		 => MPHB()->settings()->currency()->getBundle()->getLabels(),
				'default'	 => 'USD'
			) ),
			Fields\FieldFactory::create( 'mphb_currency_position', array(
				'type'		 => 'select',
				'label'		 => __( 'Currency Position', 'motopress-hotel-booking' ),
				'list'		 => MPHB()->settings()->currency()->getBundle()->getPositions(),
				'default'	 => 'before'
			) ),
			Fields\FieldFactory::create( 'mphb_datepicker_date_format', array(
				'type'		 => 'select',
				'label'		 => __( 'Datepicker Date Format', 'motopress-hotel-booking' ),
				'list'		 => MPHB()->settings()->dateTime()->getDateFormatsList(),
				'default'	 => MPHB()->settings()->dateTime()->getDefaultDateFormat()
			) ),
			Fields\FieldFactory::create( 'mphb_check_out_time', array(
				'type'		 => 'timepicker',
				'label'		 => __( 'Check-out Time', 'motopress-hotel-booking' ),
				'default'	 => '10:00'
			) ),
			Fields\FieldFactory::create( 'mphb_check_in_time', array(
				'type'		 => 'timepicker',
				'label'		 => __( 'Check-in Time', 'motopress-hotel-booking' ),
				'default'	 => '11:00'
			) ),
			Fields\FieldFactory::create( 'mphb_bed_types', array(
				'type'		 => 'complex',
				'label'		 => __( 'Bed Types', 'motopress-hotel-booking' ),
				'fields'	 => array(
					Fields\FieldFactory::create( 'type', array(
						'type'		 => 'text',
						'default'	 => '',
						'label'		 => __( 'Type', 'motopress-hotel-booking' ),
					) )
				),
				'default'	 => array(),
				'add_label'	 => __( 'Add Bed Type', 'motopress-hotel-booking' )
				)
			),
			Fields\FieldFactory::create( 'mphb_average_price_period', array(
				'type'			 => 'number',
				'label'			 => __( 'Show Lowest Price for', 'motopress-hotel-booking' ),
				'inner_label'	 => __( 'days', 'motopress-hotel-booking' ),
				'min'			 => 0,
				'step'			 => 1,
				'default'		 => 7,
				'description'	 => __( 'Lowest price of accommodation for selected number of days if check-in and check-out dates are not set. Example: set 0 to display today\'s lowest price, set 7 to display the lowest price for the next week.', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_enable_recommendation', array(
				'type'			 => 'checkbox',
				'inner_label'	 => __( 'Enable search form to recommend the best set of accommodations according to a number of guests.', 'motopress-hotel-booking' ),
				'label'			 => '',
				'default'		 => true
			) ),
			Fields\FieldFactory::create( 'mphb_enable_coupons', array(
				'type'			 => 'checkbox',
				'inner_label'	 => __( 'Enable the use of coupons.', 'motopress-hotel-booking' ),
				'default'		 => false
			) ),
			Fields\FieldFactory::create( 'mphb_checkout_text', array(
				'type'			 => 'rich-editor',
				'label'			 => __( 'Text on Checkout', 'motopress-hotel-booking' ),
				'description'	 => __( 'This text will appear on the checkout page.', 'motopress-hotel-booking' ),
				'default'		 => '',
				'translatable'	 => true
			) ),
		);

        $this->filterGroupFields($miscGroupFields, $miscGroup->getName());
		$miscGroup->addFields( $miscGroupFields );

		$bookingDisablingGroup = new Groups\SettingsGroup( 'mphb_disabling_group', __( 'Disable Booking', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );

		$bookingDisablingFields = array(
			Fields\FieldFactory::create( 'mphb_booking_disabled', array(
				'type'			 => 'checkbox',
				'inner_label'	 => __( 'Hide reservation forms and buttons', 'motopress-hotel-booking' ),
				'label'			 => '',
				'default'		 => false
			) ),
			Fields\FieldFactory::create( 'mphb_disabled_booking_text', array(
				'type'			 => 'rich-editor',
				'label'			 => __( 'Text instead of reservation form while booking is disabled', 'motopress-hotel-booking' ),
				'default'		 => false,
				'translatable'	 => true
			) )
		);

        $this->filterGroupFields($bookingDisablingFields, $bookingDisablingGroup->getName());
		$bookingDisablingGroup->addFields( $bookingDisablingFields );

		$bookingConfirmationGroup = new Groups\SettingsGroup( 'mphb_confirmation_group', __( 'Booking Confirmation', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );

		$bookingConfirmationFields = array(
			Fields\FieldFactory::create( 'mphb_confirmation_mode', array(
				'type'		 => 'radio',
				'label'		 => __( 'Confirmation Mode', 'motopress-hotel-booking' ),
				'list'		 => array(
					'auto'		 => __( 'By customer via email', 'motopress-hotel-booking' ),
					'manual'	 => __( 'By admin manually', 'motopress-hotel-booking' ),
					'payment'	 => __( 'Confirmation upon payment', 'motopress-hotel-booking' ),
				),
				'default'	 => 'auto'
			) ),
			Fields\FieldFactory::create( 'mphb_booking_confirmation_page', array(
				'type'			 => 'page-select',
				'label'			 => __( 'Booking Confirmed Page', 'motopress-hotel-booking' ),
				'description'	 => __( 'Page user will be redirected to once the booking is confirmed via email or by admin.', 'motopress-hotel-booking' ),
				'default'		 => ''
			) ),
			Fields\FieldFactory::create( 'mphb_user_approval_time', array(
				'type'			 => 'number',
				'label'			 => __( 'Approval Time for User', 'motopress-hotel-booking' ),
				'description'	 => __( 'Period of time in minutes the user is given to confirm booking via email. Unconfirmed bookings become Abandoned and accommodation status changes to Available.', 'motopress-hotel-booking' ),
				'min'			 => 5,
				'step'			 => 1,
				'default'		 => MPHB()->settings()->main()->getDefaultUserApprovalTime()
			) ),
			Fields\FieldFactory::create( 'mphb_require_country', array(
				'type'			 => 'checkbox',
				'inner_label'	 => __( 'Country of residence field is required for reservation.', 'motopress-hotel-booking' ),
				'default'		 => true
			) ),
			Fields\FieldFactory::create( 'mphb_require_full_address', array(
				'type'			 => 'checkbox',
				'inner_label'	 => __( 'Full address fields are required for reservation.', 'motopress-hotel-booking' ),
				'default'		 => false
			) ),
            Fields\FieldFactory::create( 'mphb_require_customer_on_admin', array(
                'type'           => 'checkbox',
                'inner_label'    => __( 'Customer information is required when placing admin bookings.', 'motopress-hotel-booking' ),
                'default'        => false
            ) ),
			Fields\FieldFactory::create( 'mphb_default_country', array(
				'type'			 => 'select',
				'list'			 => array( '' => __( '— Select —', 'motopress-hotel-booking' ) ) + MPHB()->settings()->main()->getCountriesBundle()->getCountriesList(),
				'label'			 => __( 'Default Country on Checkout', 'motopress-hotel-booking' ),
				'default'		 => ''
			) ),
            Fields\FieldFactory::create('mphb_unfold_price_breakdown', array(
                'type' => 'checkbox',
                'label' => __('Price Breakdown', 'motopress-hotel-booking'),
                'inner_label' => __('Price breakdown unfolded by default.', 'motopress-hotel-booking'),
                'default' => false
            ))
		);

        $this->filterGroupFields($bookingConfirmationFields, $bookingConfirmationGroup->getName());
		$bookingConfirmationGroup->addFields( $bookingConfirmationFields );

		$bookingCancellationGroup = new Groups\SettingsGroup( 'mphb_cancellation_group', __( 'Booking Cancellation', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );

		$bookingCancellationFields = array(
			Fields\FieldFactory::create( 'mphb_user_can_cancel_booking', array(
				'type'			 => 'checkbox',
				'inner_label'	 => __( 'User can cancel booking via link provided inside email.', 'motopress-hotel-booking' ),
				'default'		 => false
			) ),
			Fields\FieldFactory::create( 'mphb_booking_cancellation_page', array(
				'type'			 => 'page-select',
				'label'			 => __( 'Booking Cancelation Page', 'motopress-hotel-booking' ),
				'description'	 => __( 'Page to confirm booking cancelation.', 'motopress-hotel-booking' ),
				'default'		 => ''
			) ),
			Fields\FieldFactory::create( 'mphb_user_cancel_redirect_page', array(
				'type'			 => 'page-select',
				'label'			 => __( 'Booking Canceled Page', 'motopress-hotel-booking' ),
				'description'	 => __( 'Page to redirect to after a booking is canceled.', 'motopress-hotel-booking' ),
				'default'		 => ''
			) )
		);

        $this->filterGroupFields($bookingCancellationFields, $bookingCancellationGroup->getName());
		$bookingCancellationGroup->addFields( $bookingCancellationFields );

		$searchParametersGroup	 = new Groups\SettingsGroup( 'mphb_search_parameters', __( 'Search Options', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName(), __( 'Maximum accommodation occupancy available in the Search Form.', 'motopress-hotel-booking' ) );
		$searchParametersFields	 = array(
			Fields\FieldFactory::create( 'mphb_search_max_adults', array(
				'type'		 => 'number',
				'min'		 => 1,
				'step'		 => 1,
				'default'	 => 30, // like booking.com
				'label'		 => __( 'Max Adults', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_search_max_children', array(
				'type'		 => 'number',
				'min'		 => 1,
				'step'		 => 1,
				'default'	 => 10, // like booking.com
				'label'		 => __( 'Max Children', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_children_age', array(
				'type'		 => 'text',
				'default'	 => '',
				'label'		 => __( 'Age of Child', 'motopress-hotel-booking' ),
				'description'	 => __( 'Optional description of the "Children" field.', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_direct_booking', array(
				'type'			 => 'checkbox',
				'default'		 => false,
				'label'			 => __( 'Skip Search Results', 'motopress-hotel-booking' ),
				'inner_label'	 => __( 'Skip search results page and enable direct booking from accommodation pages.', 'motopress-hotel-booking' )
			) ),
            Fields\FieldFactory::create( 'mphb_direct_booking_price', array (
                'type'           => 'radio',
                'label'          => __( 'Direct Booking Form', 'motopress-hotel-booking' ),
                'list'           => array(
                    'disabled' => __( 'Default', 'motopress-hotel-booking' ),
                    'enabled'  => __( 'Show price for selected period', 'motopress-hotel-booking' ),
                    'capacity' => __( 'Show price together with adults and children fields', 'motopress-hotel-booking' )
                ),
                'default'        => 'disabled'
            ) ),
            Fields\FieldFactory::create( 'mphb_direct_search_results', array(
                'type'        => 'checkbox',
                'label'       => __('Book button behavior on the search results page', 'motopress-hotel-booking'),
                'inner_label' => __('Redirect to the checkout page immediately after successful addition to reservation.', 'motopress-hotel-booking'),
                'default'     => false
            ) ),
			Fields\FieldFactory::create( 'mphb_guest_management', array(
				'type'			 => 'radio',
				'list'			 => array(
					'allow-all'			 => __( 'Enable "adults" and "children" options for my website (default).', 'motopress-hotel-booking' ),
					'disable-children'	 => __( 'Disable "children" option for my website (hide "children" field and use Guests label instead).', 'motopress-hotel-booking' ),
					'disable-all'		 => __( 'Disable "adults" and "children" options for my website.', 'motopress-hotel-booking' )
				),
				'default'		 => 'allow-all',
				'label'			 => __( 'Guest Management', 'motopress-hotel-booking' ),
				'description'	 => '<code>Currently in Beta. Applies to frontend only.</code>'
			) ),
			Fields\FieldFactory::create( 'mphb_hide_guests_on_search', array(
				'type'			 => 'checkbox',
				'default'		 => false,
				'inner_label'	 => __( 'Hide "adults" and "children" fields within search availability forms.', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_do_not_apply_booking_rules_for_admin', array(
				'type'			 => 'checkbox',
				'default'		 => false,
				'label'			 => __( 'Booking Rules', 'motopress-hotel-booking' ),
				'inner_label'	 => __( 'Do not apply booking rules for admin bookings.', 'motopress-hotel-booking' )
			) )
		);

        $this->filterGroupFields($searchParametersFields, $searchParametersGroup->getName());
		$searchParametersGroup->addFields( $searchParametersFields );

		$displayGroup	 = new Groups\SettingsGroup( 'mphb_display_parameters', __( 'Display Options', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );
		$displayFields	 = array(
			Fields\FieldFactory::create( 'mphb_single_room_type_gallery_use_magnific', array(
				'type'			 => 'checkbox',
				'default'		 => true,
				'inner_label'	 => __( 'Display gallery images of accommodation page in lightbox.', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_datepicker_theme', array(
				'type'			 => 'select',
				'list'			 => MPHB()->settings()->main()->getDatepickerThemesList(),
				'default'		 => '',
				'label'			 => __( 'Calendar Theme', 'motopress-hotel-booking' ),
				'description'	 => __( 'Select theme for an availability calendar.', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_template_mode', array(
				'type'			 => 'select',
				'label'			 => __( 'Template Mode', 'motopress-hotel-booking' ),
				'list'			 => array(
					'plugin' => __( 'Developer Mode', 'motopress-hotel-booking' ),
					'theme'	 => __( 'Theme Mode', 'motopress-hotel-booking' )
				),
				'description'	 => __( 'Choose Theme Mode to display the content with the styles of your theme. Choose Developer Mode to control appearance of the content with custom page templates, actions and filters. This option can\'t be changed if your theme is initially integrated with the plugin.', 'motopress-hotel-booking' ),
				'disabled'		 => current_theme_supports( 'motopress-hotel-booking' ),
				'default'		 => 'theme'
			) )
		);

        // Since 3.8.1
        if (!defined('MPHB\Styles\VERSION')) {
            $displayFields[] = Fields\FieldFactory::create('mphb_install_styles_addon', array(
                'type'           => 'install-plugin',
                'label'          => __('More Styles', 'motopress-hotel-booking'),
                'text'           => __('Extend the styling options of Hotel Booking plugin with the new free addon - Hotel Booking Styles.', 'motopress-hotel-booking'),
                'button_classes' => 'button button-primary',
                'plugin_slug'    => 'mphb-styles/mphb-styles.php',
                'plugin_zip'     => 'https://downloads.wordpress.org/plugin/mphb-styles.zip',
                'redirect'       => admin_url('admin.php?page=mphb_settings&tab=extensions&subtab=mphb_styles')
            ));
        }

        $this->filterGroupFields($displayFields, $displayGroup->getName());
		$displayGroup->addFields( $displayFields );

        $iCalGroup = new Groups\SettingsGroup( 'mphb_ical_parameters', __( 'Calendars Synchronization', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );
        $iCalFields = array(
            Fields\FieldFactory::create( 'mphb_ical_export_blocks', array(
                'type'           => 'checkbox',
                'default'        => false,
                'inner_label'    => __( 'Export admin blocks.', 'motopress-hotel-booking' )
            ) ),
            Fields\FieldFactory::create( 'mphb_ical_dont_export_imports', array(
                'type'           => 'checkbox',
                'default'        => true,
                'inner_label'    => __( "Do not export imported bookings.", 'motopress-hotel-booking' )
            ) )
        );

        $this->filterGroupFields( $iCalFields, $iCalGroup->getName() );
        $iCalGroup->addFields( $iCalFields );

		$iCalSyncGroup = new Groups\SettingsGroup( 'mphb_ical_auto_sync_parameters', __( 'Calendars Synchronization Scheduler', 'motopress-hotel-booking' ), $generalTab->getOptionGroupName() );
		$iCalSyncFields = array(
			Fields\FieldFactory::create( 'mphb_ical_auto_sync_enable', array(
				'type'			 => 'checkbox',
				'default'		 => false,
				'inner_label'	 => __( 'Enable automatic external calendars synchronization', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_ical_auto_sync_clock', array(
				'type'			 => 'timepicker',
				'label'			 => __( 'Clock', 'motopress-hotel-booking' ),
				'inner_label'	 => __( 'Sync calendars at this time (UTC) or starting at this time every interval below.', 'motopress-hotel-booking' ),
				'default'		 => '00:00'
			) ),
			Fields\FieldFactory::create( 'mphb_ical_auto_sync_interval', array(
				'type'			 => 'select',
				'default'		 => 'daily',
				'label'			 => __( 'Interval', 'motopress-hotel-booking' ),
				'list'			 => array(
                    'mphb_15m'       => __( 'Quarter an Hour', 'motopress-hotel-booking' ),
                    'mphb_30m'       => __( 'Half an Hour', 'motopress-hotel-booking' ),
					'hourly'		 => __( 'Once Hourly', 'motopress-hotel-booking' ),
					'twicedaily'	 => __( 'Twice Daily', 'motopress-hotel-booking' ),
					'daily'			 => __( 'Once Daily', 'motopress-hotel-booking' )
				),
			) ),
            Fields\FieldFactory::create('mphb_ical_auto_delete_period', array(
                'type'           => 'select',
                'default'        => 'quarter',
                'label'          => __('Automatically delete sync logs older than', 'motopress-hotel-booking'),
                'list'           => array(
                    'day'            => __('Day', 'motopress-hotel-booking'),
                    'week'           => __('Week', 'motopress-hotel-booking'),
                    'month'          => __('Month', 'motopress-hotel-booking'),
                    'quarter'        => __('Quarter', 'motopress-hotel-booking'),
                    'half_year'      => __('Half a Year', 'motopress-hotel-booking'),
                    'never'          => __('Never Delete', 'motopress-hotel-booking')
                )
            ))
		);

        $this->filterGroupFields($iCalSyncFields, $iCalSyncGroup->getName());
		$iCalSyncGroup->addFields( $iCalSyncFields );

        // Block Editor
        $editorGroup = new Groups\SettingsGroup('mphb_block_editor', __('Block Editor', 'motopress-hotel-booking') . ' (WordPress 5.0)', $generalTab->getOptionGroupName());

        $editorGroupFields = array(
            Fields\FieldFactory::create('mphb_use_block_editor_for_room_types', array(
                'type'        => 'checkbox',
                'inner_label' => sprintf( __('Enable block editor for "%s".', 'motopress-hotel-booking'), __('Accommodation Types', 'motopress-hotel-booking') ),
                'default'     => false
            )),
            Fields\FieldFactory::create('mphb_use_block_editor_for_services', array(
                'type'        => 'checkbox',
                'inner_label' => sprintf( __('Enable block editor for "%s".', 'motopress-hotel-booking'), __('Services', 'motopress-hotel-booking') ),
                'default'     => false
            ))
        );

        $this->filterGroupFields($editorGroupFields, $editorGroup->getName());
        $editorGroup->addFields($editorGroupFields);

		$generalTab->addGroup( $pagesGroup );
		$generalTab->addGroup( $bookingConfirmationGroup );
		$generalTab->addGroup( $bookingCancellationGroup );
		$generalTab->addGroup( $searchParametersGroup );
		$generalTab->addGroup( $miscGroup );
		$generalTab->addGroup( $bookingDisablingGroup );
		$generalTab->addGroup( $displayGroup );
        $generalTab->addGroup( $iCalGroup );
		$generalTab->addGroup( $iCalSyncGroup );
        $generalTab->addGroup( $editorGroup );

		return $generalTab;
	}

	/**
	 *
	 * @return Tabs\SettingsTab
	 */
	private function _generateAdminEmailsTab(){

		$tab = new Tabs\SettingsTab( 'admin_emails', __( 'Admin Emails', 'motopress-hotel-booking' ), $this->name );

		do_action( 'mphb_generate_settings_admin_emails', $tab );

		return $tab;
	}

	/**
	 *
	 * @return Tabs\SettingsTab
	 */
	private function _generateCustomerEmailsTab(){

		$tab = new Tabs\SettingsTab( 'customer_emails', __( 'Customer Emails', 'motopress-hotel-booking' ), $this->name );

        // Add notice about the MailChimp addon at the top of the page
        if ( !ThirdPartyPluginsUtils::isActiveMphbMailchimp() ) {
            if ( MPHB()->settings()->main()->showExtensionLinks() ) {
                $mailchimpSuggestion = __( 'Turn one-time guests into loyal customers by sending out automatic marketing campaigns with the <a>Hotel Booking & Mailchimp Integration</a>.', 'motopress-hotel-booking' );
                $mailchimpSuggestion = str_replace( '<a>', '<a href="https://motopress.com/products/hotel-booking-mailchimp/?utm_source=customer_website_dashboard&utm_medium=hb_mailchimp_integration" target="_blank">', $mailchimpSuggestion );
            } else {
                $mailchimpSuggestion = __( 'Turn one-time guests into loyal customers by sending out automatic marketing campaigns with the Hotel Booking & Mailchimp Integration.', 'motopress-hotel-booking' );
            }

            $groupMailchimp = new Groups\SettingsGroup( 'mphb_mailchimp_suggestion', '', $tab->getOptionGroupName(), $mailchimpSuggestion );

            $tab->addGroup( $groupMailchimp );
        }

		do_action( 'mphb_generate_settings_customer_emails', $tab );

		$groupCancellationDetails = new Groups\SettingsGroup( 'mphb_email_cancallation_details', '', $tab->getOptionGroupName() );

		$groupCancellationDetails->addFields( array(
			Fields\FieldFactory::create( 'mphb_email_cancellation_details', array(
				'type'			 => 'rich-editor',
				'label'			 => __( 'Cancellation Details Template', 'motopress-hotel-booking' ),
				'description'	 => __( 'Used for %cancellation_details% tag.', 'motopress-hotel-booking' ) . '<br/>' . MPHB()->emails()->getCancellationTemplater()->getTagsDescription(),
				'rows'			 => 12,
				'default'		 => MPHB()->settings()->emails()->getDefaultCancellationDetailsTemplate(),
				'translatable'	 => true
			) )
		) );

		$tab->addGroup( $groupCancellationDetails );

		return $tab;
	}

	/**
	 *
	 * @return Tabs\SettingsTab
	 */
	private function _generateGlobalEmailSettingsTab(){
		$tab = new Tabs\SettingsTab( 'global_emails', __( 'Email Settings', 'motopress-hotel-booking' ), $this->name );

        // Add notice about the Notifier addon at the top of the page
        if ( !ThirdPartyPluginsUtils::isActiveMphbNotifier() ) {
            if ( MPHB()->settings()->main()->showExtensionLinks() ) {
                $notifierSuggestion = __( 'Send automated email notifications, such as key pick-up instructions, house rules, before and after arrival/departure with <a>Hotel Booking Notifier</a>.', 'motopress-hotel-booking' );
                $notifierSuggestion = str_replace( '<a>', '<a href="https://motopress.com/products/hotel-booking-notifier?utm_source=customer_website_dashboard&utm_medium=hb_notifier_addon" target="_blank">', $notifierSuggestion );
            } else {
                $notifierSuggestion = __( 'Send automated email notifications, such as key pick-up instructions, house rules, before and after arrival/departure with Hotel Booking Notifier.', 'motopress-hotel-booking' );
            }

            $groupNotifier = new Groups\SettingsGroup( 'mphb_notifier_suggestion', '', $tab->getOptionGroupName(), $notifierSuggestion );

            $tab->addGroup( $groupNotifier );
        }

		$emailGroup = new Groups\SettingsGroup( 'mphb_global_emails_settings_group', __( 'Email Sender', 'motopress-hotel-booking' ), $tab->getOptionGroupName() );

		$emailGroupFields = array(
			Fields\FieldFactory::create( 'mphb_email_hotel_admin_email', array(
				'type'			 => 'email',
				'label'			 => __( 'Administrator Email', 'motopress-hotel-booking' ),
				'default'		 => '',
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultHotelAdminEmail()
			) ),
			Fields\FieldFactory::create( 'mphb_email_from_email', array(
				'type'			 => 'email',
				'label'			 => __( 'From Email', 'motopress-hotel-booking' ),
				'default'		 => '',
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultFromEmail()
			) ),
			Fields\FieldFactory::create( 'mphb_email_from_name', array(
				'type'			 => 'text',
				'label'			 => __( 'From Name', 'motopress-hotel-booking' ),
				'default'		 => '',
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultFromName(),
				'translatable'	 => true
			) ),
			Fields\FieldFactory::create( 'mphb_email_logo', array(
				'type'			 => 'text',
				'label'			 => __( 'Logo URL', 'motopress-hotel-booking' ),
				'size'			 => 'large',
				'default'		 => '',
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultLogoUrl()
			) ),
			Fields\FieldFactory::create( 'mphb_email_footer_text', array(
				'type'			 => 'rich-editor',
				'label'			 => __( 'Footer Text', 'motopress-hotel-booking' ),
//				'description' => __('Default: ', 'motopress-hotel-booking') . MPHB()->settings()->emails()->getDefaultFooterText(),
				'rows'			 => 3,
				'default'		 => MPHB()->settings()->emails()->getDefaultFooterText(),
				'translatable'	 => true
			) ),
			Fields\FieldFactory::create( 'mphb_email_reserved_room_details', array(
				'type'			 => 'rich-editor',
				'label'			 => __( 'Reserved Accommodation Details Template', 'motopress-hotel-booking' ),
				'description'	 => __( 'Used for %reserved_rooms_details% tag.', 'motopress-hotel-booking' ) . '<br/>' . MPHB()->emails()->getReservedRoomsTemplater()->getTagsDescription(),
				'rows'			 => 24,
				'default'		 => MPHB()->settings()->emails()->getDefaultReservedRoomDetailsTemplate(),
				'translatable'	 => true
			) ),
		);

        $this->filterGroupFields($emailGroupFields, $emailGroup->getName());
		$emailGroup->addFields( $emailGroupFields );

		// Style Group
		$styleGroup = new Groups\SettingsGroup( 'mphb_global_emails_settings_style_group', __( 'Styles', 'motopress-hotel-booking' ), $tab->getOptionGroupName() );

		$styleGroupFields = array(
			Fields\FieldFactory::create( 'mphb_email_base_color', array(
				'type'			 => 'color-picker',
				'label'			 => __( 'Base Color', 'motopress-hotel-booking' ),
				'default'		 => MPHB()->settings()->emails()->getDefaultBaseColor(),
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultBaseColor()
			) ),
			Fields\FieldFactory::create( 'mphb_email_bg_color', array(
				'type'			 => 'color-picker',
				'label'			 => __( 'Background Color', 'motopress-hotel-booking' ),
				'default'		 => MPHB()->settings()->emails()->getDefaultBGColor(),
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultBGColor()
			) ),
			Fields\FieldFactory::create( 'mphb_email_body_bg_color', array(
				'type'			 => 'color-picker',
				'label'			 => __( 'Body Background Color', 'motopress-hotel-booking' ),
				'default'		 => MPHB()->settings()->emails()->getDefaultBodyBGColor(),
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultBodyBGColor()
			) ),
			Fields\FieldFactory::create( 'mphb_email_body_text_color', array(
				'type'			 => 'color-picker',
				'label'			 => __( 'Body Text Color', 'motopress-hotel-booking' ),
				'default'		 => MPHB()->settings()->emails()->getDefaultBodyTextColor(),
				'placeholder'	 => MPHB()->settings()->emails()->getDefaultBodyTextColor()
			) )
		);

        $this->filterGroupFields($styleGroupFields, $styleGroup->getName());
		$styleGroup->addFields( $styleGroupFields );

		$tab->addGroup( $emailGroup );
		$tab->addGroup( $styleGroup );

		return $tab;
	}

	/**
	 *
	 * @return Tabs\SettingsTab
	 */
	private function _generatePaymentsTab(){
		$tab = new Tabs\SettingsTab( 'payments', __( 'Payment Gateways', 'motopress-hotel-booking' ), $this->name, __( 'General Settings', 'motopress-hotel-booking' ) );

        $mainGroupDescriptions = array();

        if (!ThirdPartyPluginsUtils::isActiveMphbWoocommercePayments()) {
            $mainGroupDescriptions[] = sprintf(__('Need more gateways? Use our Hotel Booking <a href="%s" target="_blank">WooCommerce Payments</a> extension.', 'motopress-hotel-booking'), 'https://motopress.com/products/hotel-booking-woocommerce-payments/?utm_source=customer-website&utm_medium=payment-gateways-tab');
        }

        if (MPHB()->settings()->main()->showExtensionLinks() && !ThirdPartyPluginsUtils::isActiveMphbPaymentRequest()) {
            $mainGroupDescriptions[] = sprintf(__('You may also email the <a href="%s" target="_blank">balance payment request</a> link to your guests.', 'motopress-hotel-booking'), 'https://motopress.com/products/hotel-booking-payment-request/?utm_source=customer_website&utm_medium=hb_payment_request_addon');
        }

        $mainGroupDescription = implode(' ', $mainGroupDescriptions);
		$mainGroup = new Groups\SettingsGroup( 'mphb_payments_group', '', $tab->getOptionGroupName(), $mainGroupDescription );

		$mainGroupFields = array(
			Fields\FieldFactory::create( 'mphb_payment_amount_type', array(
				'type'		 => 'select',
				'label'		 => __( 'User Pays', 'motopress-hotel-booking' ),
				'list'		 => array(
					'full'		 => __( 'Full Amount', 'motopress-hotel-booking' ),
					'deposit'	 => __( 'Deposit', 'motopress-hotel-booking' )
				),
				'default'	 => MPHB()->settings()->payment()->getDefaultAmountType(),
			) ),
			Fields\FieldFactory::create( 'mphb_payment_deposit_type', array(
				'type'		 => 'select',
				'label'		 => __( 'Deposit Type', 'motopress-hotel-booking' ),
				'list'		 => array(
					'fixed'		 => __( 'Fixed', 'motopress-hotel-booking' ),
					'percent'	 => __( 'Percent', 'motopress-hotel-booking' )
				),
				'default'	 => MPHB()->settings()->payment()->getDefaultDepositType(),
			) ),
			Fields\FieldFactory::create( 'mphb_payment_deposit_amount', array(
				'type'		 => 'number',
				'label'		 => __( 'Deposit Amount', 'motopress-hotel-booking' ),
				'default'	 => MPHB()->settings()->payment()->getDefaultDepositAmount(),
				'step'		 => 0.01,
				'min'		 => 0
			) ),
            // Since 3.8.3
            Fields\FieldFactory::create( 'mphb_payment_deposit_time_frame', array(
                'type'        => 'number',
                'label'       => __( 'Deposit Time Frame (days)', 'motopress-hotel-booking' ),
                'description' => __( 'Apply deposit to bookings made in at least the selected number of days prior to the check-in date. Otherwise, the full amount is charged.', 'motopress-hotel-booking' ),
                'default'     => '',
                'step'        => 1,
                'min'         => 0,
                'allow_empty' => true
            ) ),
			Fields\FieldFactory::create( 'mphb_payment_force_checkout_ssl', array(
				'type'			 => 'checkbox',
				'label'			 => __( 'Force Secure Checkout', 'motopress-hotel-booking' ),
				'default'		 => MPHB()->settings()->payment()->getDefaultForceCheckoutSSL(),
				'inner_label'	 => __( 'Force SSL (HTTPS) on the checkout pages. You must have an SSL certificate installed to use this option.', 'motopress-hotel-booking' )
			) ),
			Fields\FieldFactory::create( 'mphb_payment_success_page', array(
				'type'		 => 'page-select',
				'label'		 => __( 'Reservation Received Page', 'motopress-hotel-booking' ),
				'default'	 => '',
			) ),
			Fields\FieldFactory::create( 'mphb_payment_failed_page', array(
				'type'		 => 'page-select',
				'label'		 => __( 'Failed Transaction Page', 'motopress-hotel-booking' ),
				'default'	 => '',
			) ),
			Fields\FieldFactory::create( 'mphb_payment_default_gateway', array(
				'type'	 => 'select',
				'label'	 => __( 'Default Gateway', 'motopress-hotel-booking' ),
				'list'	 => array( '' => '— Select —' ) + array_map( function($gateway) {
					return $gateway->getAdminTitle();
				}, MPHB()->gatewayManager()->getListActive() ),
				'default' => '',
			) ),
			Fields\FieldFactory::create( 'mphb_payment_pending_time', array(
				'type'			 => 'number',
				'label'			 => __( 'Pending Payment Time', 'motopress-hotel-booking' ),
				'description'	 => __( 'Period of time in minutes the user is given to complete payment. Unpaid bookings become Abandoned and accommodation status changes to Available.', 'motopress-hotel-booking' ),
				'min'			 => 5,
				'step'			 => 1,
				'default'		 => MPHB()->settings()->payment()->getDefaultPendingTime()
			) )
		);

        $this->filterGroupFields($mainGroupFields, $mainGroup->getName());
		$mainGroup->addFields( $mainGroupFields );

		$tab->addGroup( $mainGroup );

		do_action( 'mphb_generate_settings_payments', $tab );

		return $tab;
	}

    private function _generateExtensionsTab()
    {
        $tab = new Tabs\SettingsTab('extensions', __('Extensions', 'motopress-hotel-booking'), $this->name);

        if (MPHB()->settings()->main()->showExtensionLinks()) {
            $mainGroupDescription = sprintf(__('Install <a href="%s" target="_blank">Hotel Booking addons</a> to manage their settings.', 'motopress-hotel-booking'), 'https://motopress.com/products/category/hotel-booking-addons/?utm_source=customer_website&utm_medium=hotel_booking_addons');
        } else {
            $mainGroupDescription = __('Install Hotel Booking addons to manage their settings.', 'motopress-hotel-booking');
        }

        $mainGroup = new Groups\SettingsGroup('mphb_extensions_group', '', $tab->getOptionGroupName(), $mainGroupDescription);

        $tab->addGroup($mainGroup);

        // Generate extension subtabs
        do_action('mphb_generate_extension_settings', $tab);

        return $tab;
    }

	/**
	 *
	 * @return Tabs\SettingsTab
	 */
	private function _generateLicenseTab(){
		$tab = new Tabs\SettingsTab( 'license', __( 'License', 'motopress-hotel-booking' ), $this->name );

		$licenseGroup = new Groups\LicenseSettingsGroup( 'mphb_license_group', __( 'License', 'motopress-hotel-booking' ), $tab->getOptionGroupName() );

		$tab->addGroup( $licenseGroup );

		return $tab;
	}

    protected function filterGroupFields(&$groupFields, $groupSlug)
    {
        $customFields = apply_filters('mphb_custom_group_fields', array(), $groupSlug);

        if (!empty($customFields)) {
            $groupFields = array_merge($groupFields, $customFields);
        }
    }

	public function addActions(){
		parent::addActions();
		add_action( 'admin_init', array( $this, 'initFields' ) );
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
        add_action( 'admin_notices', array( $this, 'renderNotices' ), 186400 );
	}

	public function enqueueAdminScripts(){
		if ( $this->isCurrentPage() ) {
			MPHB()->getAdminScriptManager()->enqueue();
		}
	}

	public function onLoad(){
		$this->save();
	}

	public function render(){
		echo '<div class="wrap">';
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
			add_settings_error( 'mphbSettings', esc_attr( 'settings_updated' ), __( 'Settings saved.', 'motopress-hotel-booking' ), 'updated' );
		}
		settings_errors( 'mphbSettings', false );
		$this->renderTabs();
		$tabName = $this->detectTab();
		if ( isset( $this->tabs[$tabName] ) ) {
			$this->tabs[$tabName]->render();
		}
		echo '</div>';
	}

	private function renderTabs(){
		echo '<h1 class="nav-tab-wrapper">';
		if ( is_array( $this->tabs ) ) {
			foreach ( $this->tabs as $tabId => $tab ) {
				$class = ($tabId == $this->detectTab()) ? ' nav-tab-active' : '';
				echo '<a href="' . esc_url( add_query_arg( array( 'page' => $this->name, 'tab' => $tabId ), admin_url( 'admin.php' ) ) ) . '" class="nav-tab' . $class . '">' . esc_html( $tab->getLabel() ) . '</a>';
			}
		}
		echo '</h1>';
	}

    public function renderNotices()
    {
        $activeTab = $this->detectTab();
        $paymentMethod = MPHB()->settings()->main()->getConfirmationMode();

        if ($activeTab == 'payments' && $paymentMethod != 'payment') {
            echo '<div class="notice notice-warning">';
            echo '<p>', __('<strong>Note:</strong> Payment methods will appear on the checkout page only when Confirmation Upon Payment is enabled in Accommodation > Settings > General > Confirmation Mode.', 'motopress-hotel-booking'), '</p>';
            echo '</div>';
        }
    }

    /**
     * @since 3.7.0 Became public (was private before).
     */
	public function detectTab(){
		$defaultTab	 = 'general';
		$tab		 = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $defaultTab;
		return $tab;
	}

	/**
	 * @return string
     *
     * @since 3.7.0 Became public (was private before).
	 */
	public function detectSubTab(){
		$tab = $this->detectTab();
		$tab = isset( $this->tabs[$tab] ) ? $this->tabs[$tab]->detectSubTab() : '';

        if (empty($tab)) {
            $tab = isset($_GET['subtab']) ? sanitize_text_field($_GET['subtab']) : '';
        }

        return $tab;
	}

	public function save(){
		$tabName = $this->detectTab();
		if ( isset( $this->tabs[$tabName] ) && !empty( $_POST ) && current_user_can( 'manage_options' ) ) {
			$this->tabs[$tabName]->save();
		}

		// Update iCalendar autosynchronization cron
		$autoSyncEnabled  = (bool) get_option( 'mphb_ical_auto_sync_enable', false );
		$autoSyncClock    = get_option( 'mphb_ical_auto_sync_clock', false );
		$autoSyncInterval = get_option( 'mphb_ical_auto_sync_interval', false );

		MPHB()->cronManager()->rescheduleAutoSynchronization( $autoSyncEnabled, $autoSyncClock, $autoSyncInterval );

        // Disable/enable DeleteOldSyncLogsCron
        $autoDeletePeriod = MPHB()->settings()->main()->deleteSyncLogsOlderThan();

        if ($autoDeletePeriod != 'never') {
            MPHB()->cronManager()->getCron('ical_auto_delete')->schedule();
        } else {
            MPHB()->cronManager()->getCron('ical_auto_delete')->unschedule();
        }
	}

	public function registerSettings(){
		foreach ( $this->tabs as $tab ) {
			$tab->register();
		}
	}

	/**
	 *
	 * @param array $atts
	 * @param string $atts['tab'] Name of tab to check
	 * @param string $atts['subtab'] Name of subtab to check
	 * @return boolean
	 */
	public function isCurrentPage( $atts = array() ){

		$isCurrentPage = parent::isCurrentPage( $atts );

		if ( !$isCurrentPage ) {
			return false;
		}

		if ( !empty( $atts['tab'] ) && ( $this->detectTab() !== $atts['tab'] ) ) {
			return false;
		}

		if ( !empty( $atts['subtab'] ) && ( $this->detectSubTab() !== $atts['subtab'] ) ) {
			return false;
		}

		return true;
	}

	protected function getMenuTitle(){
		return __( 'Settings', 'motopress-hotel-booking' );
	}

	protected function getPageTitle(){
		return __( 'Settings', 'motopress-hotel-booking' );
	}

	/**
	 * Retrieve Url of Motopress Hotel Booking Settings Page
	 *
	 * @param array $additionalArgs
	 * @param string $additionalArgs['tab'] Tab Name.
	 * @param string $additionalArgs['subtab'] Sub Tab Name.
	 *
	 */
	public function getUrl( $additionalArgs = array() ){
		return parent::getUrl( $additionalArgs );
	}

}
