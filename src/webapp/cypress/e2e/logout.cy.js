describe('Log out process', () => {
  it('Log out', () => {
    // go to login page
    cy.visit('/login')
    cy.get('#input-email').type('admin@admin.com')
    cy.get('#input-password').type('admin')
    cy.get('button[type=submit]').click()

    // we should be redirected to /dashboard
    cy.url()
      .should('include', '/dashboard')
      .then(() => {
        // dropdown user menu
        cy.get('li.nav-item.b-nav-dropdown a[role=button]')
          .first()
          .click()
          .then(() => {
            // click on log out button
            cy.get(':nth-child(2) > .dropdown-item').click()
          })
      })
  })
})
