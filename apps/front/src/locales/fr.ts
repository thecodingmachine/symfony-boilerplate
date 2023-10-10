export default {
  components: {
    user: {
      createForm: {
        title: "Créer un nouvel utilisateur",
        ok: "Créer",
      },
      updateForm: {
        pending: "Chargement de l'utilisateur",
        title: "Mise à jour de {email}",
        ok: "Mettre à jour",
      },
      form: {
        email: "Email",
        password: "Mot de passe",
        passwordConfirm: "Confirmez votre mot de passe",
        errorPasswordConfirm: "La confirmation du mot de passe est invalide",
      },
    },
    layout: {
      appHeader: {
        welcome: "Bienvenue {username}",
      },
      menu: {
        appMenu: {
          users: "Utilisateurs",
          page1: "page1",
          page2: "page2",
          page3: "page3",
          quit: "Quitter",
        },
      },
    },
  },
  pages: {
    page1: {
      altPanda: "Découvrez les images",
      background: "Découvrez les fonds d'écrans",
    },
    auth: {
      login: {
        username: "email",
        password: "mot de passe",
        title: "Connectez-vous",
        ok: "Connexion",
      },
    },
    user: {
      index: {
        createButton: "Créer un utilisateur",
        pending: "Chargement des utilisateurs",
        title: "Utilisateurs",
        edit: "Editer",
        delete: "Supprimer",
      },
    },
  },
};
