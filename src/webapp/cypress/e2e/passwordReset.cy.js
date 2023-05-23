describe('Submit ', () => {
  it('login as simple user', () => {
    // go to login page
    cy.visit('/reset-password')
    cy.get('#input-email').type('user@user.com')
    cy.get('button[type=submit]').click()

    // check form the message of email sent
    cy.get('h5').contains('user@user.com')
    cy.get('.card-body .text-center p').should('have.length', 2)
  })
})
