describe('Create new user', () => {
  it('login as admin and create new user', () => {
    // go to login page
    cy.visit('/login')
    cy.get('#input-email').type('admin@admin.com')
    cy.get('#input-password').type('admin')
    cy.get('button[type=submit]').click()

    // we should be redirected to /dashboard
    cy.url()
      .should('include', '/dashboard')
      .then(() => {
        // click on users menu
        cy.get('.pt-3 > .nav > :nth-child(6) > .nav-item > .nav-link')
          .should('have.attr', 'href', '/dashboard/admin/users')
          .click()
          .then(() => {
            // click on create button
            cy.get('.m-auto > .btn-primary')
              .should('have.attr', 'href', '/dashboard/admin/users/create')
              .click()

            // field all required fields
            cy.get('#input-first-name').type('new-user')
            cy.get('#input-last-name').type('test')
            cy.get('#input-email').type('newuser@test.com')
            cy.get('#input-locale').select('FR')
            cy.get('#input-role').select('USER')
            cy.get('form > .btn')
              .click() // submit the form
              .then(() => {
                cy.url().should('match', /(\/dashboard\/admin\/users\/)/)
              })
          })
      })
  })
})
