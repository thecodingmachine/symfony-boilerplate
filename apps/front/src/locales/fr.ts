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
      tableHeaders: {
        email: "Email",
        firstName: "Prénom",
        lastName: "Nom",
        score: "Score",
        pendingPayments: "Nbr paiements non localisés"
      }
    },

    payment: {
      index: {
        locatebutton: "Identifier le lieu",
        pending: "Chargement des paiements non localisés ",
        title: "Paiements non localisés ",
        edit: "Editer",
        delete: "Supprimer",
        successAlertTitle: "Succès",
        successAlertDescription: "Votre lieu a bien été enregistré",
        table_date: "Date / heure",
        table_montant: "Montant",
        table_label: "Libellé du paiment",
        table_location: "Localisation",
        choosePlaceHeader: "Choisir le lieu",
        searchingPlaces: "Recherche des lieux...",
        noPlacesFound: "Aucun lieu trouvé dans le rayon de 1Km pour ce paiement!",
        closeModalButton: "Fermer"

      },
    },
    dashboard: {
      score: {
        title: "Mon Score:",
        defaultScore: "0"
      },
      pendingOperations: {
        title: "Opérations en attente:",
        defaultPending: "0"
      }
    }
    
  },
};
