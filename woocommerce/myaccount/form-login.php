<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>




    <div class="u-columns col2-set" id="customer_login">

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) && isset( $_GET['action'] ) && $_GET['action'] == 'sign-in' ) : ?>

    <div class="u-column1 audioteria-login">


        <header>
            <a href="<?= home_url( '/' ) ?>">
                <img class="nav-main-logo" src="<?= isset( $image[0] ) ? $image[0] : AUDIOTERIA_CUSTOM_LOGO; ?>"
                     alt="<?= get_bloginfo( 'name', 'display' ) ?>" title="<?= get_bloginfo( 'name', 'display' ) ?>">
            </a>
            <h2>
				<?= __( 'Get Access to Unlimited Audiobooks at
                Audioteria', 'audioteria-wp' ) ?>
            </h2>
        </header>

        <p class="login-error"></p>

        <form id="woo_login_form" class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                       id="username" autocomplete="username" placeholder="Email"
                       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine
				?>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                       placeholder="Password" name="password" id="password" autocomplete="current-password"/>
            </p>

			<?php do_action( 'woocommerce_login_form' ); ?>

            <div class="forgot-password-wrapper">
                <div class="update">

                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
                           type="checkbox" id="rememberme" value="forever"/>
                    <p><?php esc_html_e( 'Keep me signed in', 'audioteria-wp' ); ?></p>

                </div>


                <p class="woocommerce-LostPassword lost_password">
                    <a
                            href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'audioteria-wp' ); ?></a>
                </p>
            </div>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            <button type="submit" id="woo-custom__login"
                    class="woocommerce-button button woocommerce-form-login__submit" name="login"
                    value="<?php esc_attr_e( 'Sign in', 'audioteria-wp' ); ?>"><?php esc_html_e( 'Sign in', 'audioteria-wp' ); ?></button>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

        </form>
        <p class="link-sign-up"><?= __( 'Don\'t have an account?', 'audioteria-wp' ) ?>
            <a title="sign in"
               href="<?= esc_url_raw( add_query_arg( [ 'action' => 'register' ] ) ) ?>">
				<?= __( 'Sign up', 'audioteria-wp' ) ?> </a>
        </p>


    </div>

    <!-- //else get registration form -->


<?php elseif ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) && isset( $_GET['action'] ) && $_GET['action'] == 'register' ) : ?>

    <div class="u-column2 audioteria-register ">

        <!-- custom registration header -->
        <header>
            <a href="<?= home_url() ?>">
                <img class="nav-main-logo" src="<?= isset( $image[0] ) ? $image[0] : AUDIOTERIA_CUSTOM_LOGO; ?>"
                     alt="<?= get_bloginfo( 'name', 'display' ) ?>" title="<?= get_bloginfo( 'name', 'display' ) ?>">
            </a>
            <h2>
				<?= __( 'Become a Member of this Community', 'audioteria-wp' ) ?>
            </h2>
            <p>
				<?= __( 'Be part of the online community and get access
                endless audiobooks of your choice', 'audioteria-wp' ) ?>
            </p>
        </header>
        <p class="login-error"></p>
        <!-- custom registration form -->
        <div class="u-column2">


            <form method="post" class="woocommerce-form woocommerce-form-register register" id="audioteria_reg_custom"
				<?php do_action( 'woocommerce_register_form_tag' ); ?>>

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>


				<?php endif; ?>


                <input type="email" placeholder="Email" class="woocommerce-Input woocommerce-Input--text input-text"
                       name="email" id="reg_email" autocomplete="email"
                       value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine
				?>


				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                  
				<?php else : ?>

                    <p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?>
                    </p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

                <p class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <button type="submit"
                            class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit"
                            name="register"
                            onclick="signUpValidation()"
                            value="<?php esc_attr_e( 'Sign Up', 'woocommerce' ); ?>"><?php esc_html_e( 'Sign Up', 'audioteria-wp' ); ?></button>
                </p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>
                <p class="link-sign-in"><?= __( 'Already a member?', 'audioteria-wp' ) ?>
                    <a title="sign in"
                       href="<?= esc_url_raw( add_query_arg( [ 'action' => 'login' ] ) ) ?>"><?= __( 'Sign in', 'audioteria-wp' ) ?> </a>
                </p>
            </form>

        </div>


    </div>


    <!-- This shows up when no action parameter is set  -->
<?php else : ?>

    <div class="u-column1 audioteria-login">


        <header>
            <a href="<?= home_url( '/' ) ?>">
                <img class="nav-main-logo" src="<?= isset( $image[0] ) ? $image[0] : AUDIOTERIA_CUSTOM_LOGO; ?>"
                     alt="<?= get_bloginfo( 'name', 'display' ) ?>" title="<?= get_bloginfo( 'name', 'display' ) ?>">
            </a>
            <h2>
				<?= __( 'Get Access to Unlimited Audiobooks at
                Audioteria', 'audioteria-wp' ) ?>
            </h2>
        </header>

        <p class="login-error"></p>
        <form id="woo_login_form" class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                       id="username" autocomplete="username" placeholder="Email"
                       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine
				?>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">

                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                       placeholder="Password" name="password" id="password" autocomplete="current-password"/>
            </p>

			<?php do_action( 'woocommerce_login_form' ); ?>

            <div class="forgot-password-wrapper">
                <div class="update">

                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
                           type="checkbox" id="rememberme" value="forever"/>
                    <p><?php esc_html_e( 'Keep me signed in', 'audioteria-wp' ); ?></p>

                </div>

                <div class="update">
                    <p class="woocommerce-LostPassword lost_password">
                        <a
                                href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'audioteria-wp' ); ?></a>
                    </p>
                </div>
            </div>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            <button type="submit" id="woo-custom__login"
                    class="woocommerce-button button woocommerce-form-login__submit" name="login"
                    value="<?php esc_attr_e( 'Sign in', 'audioteria-wp' ); ?>"><?php esc_html_e( 'Sign in', 'audioteria-wp' ); ?></button>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

        </form>
        <p class="link-sign-up"><?= __( 'Don\'t have an account?', 'audioteria-wp' ) ?> <a title="sign up"
                                                                                           href="<?= esc_url_raw( add_query_arg( [ 'action' => 'register' ] ) ) ?>">
				<?= __( 'Sign up', 'audioteria-wp' ) ?> </a>
        </p>


    </div>

    </div>
<?php endif; ?>


<?php do_action( 'woocommerce_after_customer_login_form' ); ?>