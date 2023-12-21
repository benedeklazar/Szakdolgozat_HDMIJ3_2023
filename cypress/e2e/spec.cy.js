describe('Szakdolgozat teszt', () => {
  it('passes', () => {
    //
    cy.visit('http://localhost:8000/')

    // A Felhasználónév mező üresen hagyása
    cy.contains('Regisztráció').click()
    cy.get('input[id=username]').type(' ')
    cy.get('input[id=password]').type('abcabc')
    cy.get('input[id=password-confirm]').type('abcabc')
    cy.get('button[id=submit]').click()

    cy.contains('A Felhasználónév mező nem lehet üres.').should('be.visible')

    // A Jelszó mező üresen hagyása
    cy.reload()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type(' ')
    cy.get('input[id=password-confirm]').type(' ')
    cy.get('button[id=submit]').click()

    cy.contains('A Jelszó mező nem lehet üres.').should('be.visible')

    // Regisztráció túl rövid felhasználónévvel
    cy.reload()
    cy.get('input[id=username]').type('abc')
    cy.get('input[id=password]').type('abcabc')
    cy.get('input[id=password-confirm]').type('abcabc')
    cy.get('button[id=submit]').click()
    
    cy.contains('A Felhasználónév legyen legalább 5 karakter hosszú.').should('be.visible')

    // Regisztráció túl rövid jelszóval
    cy.reload()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type('abc')
    cy.get('input[id=password-confirm]').type('abc')
    cy.get('button[id=submit]').click()
    
    cy.contains('A Jelszó legyen legalább 5 karakter hosszú.').should('be.visible')

    // Regisztráció elgépelt jelszó párral
    cy.reload()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type('abcabc')
    cy.get('input[id=password-confirm]').type('abcabd')
    cy.get('button[id=submit]').click()
    
    cy.contains('A Jelszó újra nem egyezik a Jelszó-val.').should('be.visible')

    // Regisztráció helyes adatokkal
    cy.reload()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type('abcabc')
    cy.get('input[id=password-confirm]').type('abcabc')
    cy.get('button[id=submit]').click()

    cy.contains('Üdvözöljük TEST_01!').should('be.visible')

    // Kijelentkezés tesztelése
    cy.get('a[id=navbarDropdown]').click()
    cy.contains('Kijelentkezés').click()

    cy.contains('Bejelentkezés').should('be.visible')

    // Regisztráció ugyanazzal a felhasználónévvel
    cy.contains('Regisztráció').click()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type('abcabc')
    cy.get('input[id=password-confirm]').type('abcabc')
    cy.get('button[id=submit]').click()

    cy.contains('Ez a Felhasználónév már foglalt.').should('be.visible')

    // Bejelentkezés nem létező felhasználóval
    cy.contains('Bejelentkezés').click()
    cy.get('input[id=username]').type('NEMLETEZO_FELHASZNALO')
    cy.get('input[id=password]').type('abcabc')
    cy.get('button[id=submit]').click()

    cy.contains('Hibás felhasználónév vagy jelszó.').should('be.visible')

    // Bejelentkezés hibás jelszóval
    cy.reload()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type('abcabd')
    cy.get('button[id=submit]').click()

    cy.contains('Hibás felhasználónév vagy jelszó.').should('be.visible')

    // Bejelentkezés helyes adatokkal
    cy.reload()
    cy.get('input[id=username]').type('TEST_01')
    cy.get('input[id=password]').type('abcabc')
    cy.get('button[id=submit]').click()

    cy.contains('Üdvözöljük TEST_01!').should('be.visible')

    //Felhasználónév módosítása már létező felhasználóra
    cy.get('a[id=navbarDropdown]').click()
    cy.contains('Fiók szerkesztése').click()

    cy.get('input[id=username]').clear()
    cy.get('input[id=username]').type('admin') //létező username
    cy.get('button[id=submit]').click()

    cy.contains('Ez a Felhasználónév már foglalt.').should('be.visible')
    //Rossz fájltípusú fájl megadása Profilképként
    cy.reload()
    cy.get('input[name=image]').selectFile('cypress/files/test.txt') //txt kiterjesztésű fájl feltöltése
    cy.get('button[id=submit]').click()

    cy.contains('A Kép-nek egy képnek kell lennie.').should('be.visible')

    //Felhasználónév módosítása új felhasználónévre
    cy.reload()
    cy.get('input[id=username]').clear()
    cy.get('input[id=username]').type('TEST_02') //új username
    cy.get('button[id=submit]').click()

    cy.contains('TEST_02').should('be.visible')
    cy.contains('Műveletek').should('be.visible')

    //Felhasználó profilképének módosítása
    cy.contains('TEST_02').click()
    cy.contains('Fiók szerkesztése').click()

    cy.get('input[name=image]').selectFile('cypress/files/test.jpg') //jpg kiterjesztésű fájl feltöltése
    cy.get('button[id=submit]').click()

    cy.contains('TEST_02').should('be.visible')
    cy.contains('Műveletek').should('be.visible')

  })
})