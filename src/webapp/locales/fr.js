export default {
  // All translations that you use in different places.
  common: {
    email: {
      label: 'Email',
      label_required: 'Email *',
      placeholder: 'Entrer votre email',
    },
    login: 'Se connecter',
    logout: 'Se déconnecter',
    submit: 'Envoyer',
    retry: 'Réessayer',
    create: 'Créer',
    update: 'Mettre à jour',
    delete: 'Supprimer',
    send_email: "Envoyer l'email",
    all: 'Tous',
    multiple_files: {
      placeholder: 'Choisir des fichiers ou glisser/déposer les ici...',
      drop_placeholder: 'Déposer les fichiers ici...',
    },
    browse_files: 'Parcourir',
    reset_files: 'Réinitialiser les fichiers',
    roles: {
      select: 'Sélectionner un rôle',
      administrator: 'Administateur',
      merchant: 'Marchand',
      client: 'Client',
    },
  },
  // Translations of your components.
  components: {
    layouts: {
      header: {
        administration: 'Administration',
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
