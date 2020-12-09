export default {
  // All translations that you use in different places.
  common: {
    email: {
      label: 'Email',
      label_required: 'Email *',
      placeholder: 'Entrer votre email',
    },
    submit: 'Envoyer',
    retry: 'Réessayer',
    create: 'Créer',
    update: 'Mettre à jour',
    delete: 'Supprimer',
    send_email: "Envoyer l'email",
    all: 'Tous',
    multiple_files: {
      placeholder: 'Choisir des fichiers',
      drop_placeholder: 'Déposer les fichiers',
    },
    single_file: {
      placeholder: 'Choisir un fichier',
      drop_placeholder: 'Déposer le fichier',
    },
    browse: 'Parcourir',
    reset: 'Réinitialiser',
    roles: {
      select: 'Sélectionner un rôle',
      administrator: 'Administateur',
      user: 'Utilisateur',
    },
    user: {
      profile_picture: 'Photo de profil',
    },
    nav: {
      login: 'Se connecter',
      logout: 'Se déconnecter',
      my_profile: 'Mon profil',
      dashboard: 'Tableau de bord',
      administration: 'Administration',
      users: 'Utilisateurs',
    },
  },
  // Translations of your components.
  components: {
    layouts: {
      header: {
        dashboard: 'Tableau de bord',
      },
      left_menu: {
        my_profile: 'Mon profil',
        administration: 'Administration',
        users: 'Utilisateurs',
      },
    },
  },
  // Translations of your layouts.
  layouts: {
    error: {
      generic_message: 'Une erreur est survenue',
      not_found_message: 'Page non trouvée',
      access_forbidden_message: 'Accès interdit',
      home_page: "Page d'accueil",
    },
  },
  // Translations of your mixins.
  mixins: {},
  // Translations of your pages.
  pages: {
    login: {
      password: {
        label_required: 'Mot de passe *',
        placeholder: 'Entrer votre mot de passe',
      },
      error_message: "L'email ou le mot de passe fourni est incorrect.",
      forgot_password: "J'ai oublié mon mot de passe",
    },
    reset_password: {
      success_message:
        "Si l'adresse existe dans notre système, un email a été envoyé avec des instructions pour vous aider à changer votre mot de passe",
    },
    update_password: {
      new_password: {
        label_required: 'Nouveau mot de passe *',
        placeholder: 'Entrer votre nouveau mot de passe',
      },
      password_confirmation: {
        label_required: 'Confirmation du nouveau mot de passe *',
        placeholder: 'Entrer une nouvelle fois votre nouveau mot de passe',
      },
      invalid_token_message: 'Votre jeton a expiré ou il est invalide.',
      success_message: 'Votre mot de passe a été mise à jour.',
    },
  },
}
