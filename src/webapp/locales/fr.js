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
    edit: 'Éditer',
    delete: 'Supprimer',
    confirm: 'Confirmer',
    cancel: 'Annuler',
    send_email: "Envoyer l'email",
    browse: 'Parcourir',
    reset: 'Réinitialiser',
    search: 'Rechercher',
    export: 'Exporter',
    all: 'Tous',
    multiple_files: {
      placeholder: 'Choisir des fichiers',
      drop_placeholder: 'Déposer les fichiers',
    },
    single_file: {
      placeholder: 'Choisir un fichier',
      drop_placeholder: 'Déposer le fichier',
    },
    user: {
      first_name: {
        label: 'Prénom',
        label_required: 'Prénom *',
        placeholder: 'Entrer un prénom',
      },
      last_name: {
        label: 'Nom',
        label_required: 'Nom *',
        placeholder: 'Entrer un nom',
      },
      locale: {
        label: 'Langue',
        label_required: 'Langue *',
        select: 'Sélectionner une langue',
      },
      role: {
        label: 'Rôle',
        label_required: 'Rôle *',
        select: 'Sélectionner un rôle',
        administrator: 'Administateur',
        user: 'Utilisateur',
      },
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
    list: {
      actions: 'Actions',
    },
  },
  // Translations of your components.
  components: {
    forms: {
      confirm_delete: {
        enter_confirm: 'Entrer "Confirmer"',
        danger_zone_message:
          'Attention, cette zone permet de réaliser des actions dangeureuses et irrémédiables.',
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
  mixins: {
    generic_toast: {
      success_message: 'Succès 🎉',
    },
  },
  // Translations of your pages.
  pages: {
    home: {
      welcome: 'Bienvenue !',
      message:
        'Le Symfony Boilerplate fournit une application factice avec des concepts et des fonctionnalités de base pour vous aider à créer une application web moderne.',
    },
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
