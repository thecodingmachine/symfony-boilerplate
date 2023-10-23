export default {
  components: {
    user: {
      createForm: {
        title: "Create a new user",
        ok: "Create",
      },
      updateForm: {
        pending: "Loading user",
        title: "Update {email}",
        ok: "Update",
      },
      form: {
        email: "Email",
        password: "Password",
        passwordConfirm: "Confirm your password",
        errorPasswordConfirm: "The confirmation of the password is invalid",
      },
    },
    layout: {
      appHeader: {
        welcome: "Welcome {username}",
      },
      menu: {
        appMenu: {
          users: "Users",
          page1: "page1",
          page2: "page2",
          page3: "page3",
          quit: "Quit",
        },
      },
    },
  },
  pages: {
    page1: {
      altPanda: "This is to discover images",
      background: "This is to discover background",
    },
    auth: {
      login: {
        username: "Email",
        password: "Password",
        title: "Please log-in",
        ok: "Submit",
      },
    },
    user: {
      index: {
        createButton: "New user",
        pending: "Loading users",
        title: "Users",
        edit: "Edit",
        delete: "Delete",
      },
    },
  },
  plugins: {
    appFetch: {
      toasterUnauthorizedDetail: "Unauthorized",
      toasterUnauthorizedSummary: "Unauthorized",

      toasterForbiddenDetail: "Forbidden",
      toasterForbiddenSummary: "Forbidden",

      toasterCatchAllDetail: "Error",
      toasterCatchAllSummary: "Error",
    },
    error: {
      toasterCatchAllDetail: "Error",
      toasterCatchAllSummary: "Error",
    },
  },
};
