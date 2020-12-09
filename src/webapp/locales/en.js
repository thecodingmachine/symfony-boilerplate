export default {
  // All translations that you use in different places.
  common: {
    email: {
      label: 'Email',
      label_required: 'Email *',
      placeholder: 'Enter your email',
    },
    submit: 'Submit',
    retry: 'Retry',
    create: 'Create',
    update: 'Update',
    delete: 'Delete',
    send_email: 'Send email',
    all: 'All',
    multiple_files: {
      placeholder: 'Choose files',
      drop_placeholder: 'Drop files',
    },
    single_file: {
      placeholder: 'Choose a file',
      drop_placeholder: 'Drop file',
    },
    browse: 'Browse',
    reset: 'Reset',
    roles: {
      select: 'Select a role',
      administrator: 'Administrator',
      user: 'User',
    },
    user: {
      profile_picture: 'Profile picture',
    },
    nav: {
      login: 'Login',
      logout: 'Logout',
      my_profile: 'My profil',
      dashboard: 'Dashboard',
      administration: 'Administration',
      users: 'Users',
    },
  },
  // Translations of your components.
  components: {},
  // Translations of your layouts.
  layouts: {
    error: {
      generic_message: 'An error occurred',
      not_found_message: 'Page not found',
      access_forbidden_message: 'Access forbidden',
      home_page: 'Home page',
    },
  },
  // Translations of your mixins.
  mixins: {},
  // Translations of your pages.
  pages: {
    login: {
      password: {
        label_required: 'Password *',
        placeholder: 'Enter your password',
      },
      error_message: 'The provided email or password is incorrect.',
      forgot_password: 'I forgot my password',
    },
    reset_password: {
      success_message:
        'If the address exists in our system, an email has been delivered with instructions to help you change your password.',
    },
    update_password: {
      new_password: {
        label_required: 'New password *',
        placeholder: 'Enter your new password',
      },
      password_confirmation: {
        label_required: 'Password confirmation *',
        placeholder: 'Enter again your new password',
      },
      invalid_token_message: 'Your token has either expired or is invalid.',
      success_message: 'Your password has been updated.',
    },
  },
}
