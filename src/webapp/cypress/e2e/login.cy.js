describe('login process', () => {

  it('login as simple user', () => {
    // go to login page
    cy.visit('/login')
    cy.get('#input-email').type('user@user.com')
    cy.get('#input-password').type('user')
    cy.get('button[type=submit]').click()

    // we should be redirected to /dashboard
    cy.url().should('include', '/dashboard')
  })


  it('login as admin', () => {
    // go to login page
    cy.visit('/login')
    cy.get('#input-email').type('admin@admin.com')
    cy.get('#input-password').type('admin')
    cy.get('button[type=submit]').click()

    // we should be redirected to /dashboard
    cy.url().should('include', '/dashboard')
  })
})
