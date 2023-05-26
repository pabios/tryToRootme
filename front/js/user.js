let info = document.querySelector('#info');
let envoyer = document.querySelector('#login')
let leForm = document.querySelector('#loginform')
let leNom = document.querySelector('#pseudo')
let mdp = document.querySelector('#password')



// affiche resultat
let lien = document.querySelector('.apiGet')
let resultat = document.querySelector('.resultat')
let ligne = document.querySelector('.ligne')



writeCooki =(leCookie)=>{
    sessionStorage.setItem('bigbrother', leCookie);

    // let valeurSession = sessionStorage.getItem('bigbrother');
    // console.log(valeurSession);console.log('le resultat du cooki')
}

const api = 'http://localhost:80/api/signin';

/**
 *  la fonction callback envoyer a l'evenement submit
 */
postApi = (e) => {
    e.preventDefault();
    let dataForm = new FormData(leForm);

    fetch(api,{
        method: "POST",
        body:  dataForm
    })
        .then(res => {
            if(res.ok){
                return res.json()
            }
        })
        .then(data =>{
            // console.log(data)
            let roles = ["admin","user"]
            sessionStorage.setItem('bigbrother', data);
            // alert(sessionStorage.getItem('bigbrother'))
            if(roles.includes(data.toString())){

                    lien.addEventListener('click',getApi,false)
                alert(`welcome back vous avez le role | ${data} | `)

            }else{
                alert('identifiant incorect');
            }
        })
        .catch(e => {
            console.log('erreur de lecture'+e);
        })
}

leForm.addEventListener('submit',postApi,false)



/**
 * fetch api
 * method -> get
 * return json
 */

getApi = (e) => {
    e.preventDefault();

    let apiGet = 'http://localhost:80/api/users'

    fetch(apiGet)
        .then(res => {
            if(res.ok){
                return res.json()
            }
        })
        .then(data =>{
            console.log(data)

            if (resultat.childElementCount === 1) {
                let action = 'user';
                let buttonAction = ` 
                  <button name="button">le user connecter n'est pas admin</button>
                `

                let role = sessionStorage.getItem('bigbrother');
                if(role === 'admin'){
                    buttonAction   = ` 
                              <button name="button">supprimer car tu es admin</button>
                            `
                }
                data.forEach(elm => {
                    resultat.innerHTML += `
                    <tr>
                        <td> 
                            ${elm.id}
                        </td>
                        <td> 
                            ${elm.pseudo}
                        </td>
                        <td> 
                        ${elm.description}
                        </td>
                        <td> 
                        ${elm.role}
                        </td>
                         <td> 
                              ${buttonAction} 
                        </td>
                     </tr>
                </ul> 
             `
                });
            } else {
                console.log("les donee sont deja afficheee");
            }

        })
        .catch(e => {
            console.log('erreur de lecture'+e);
        })
}

// if(sessionStorage.getItem('bigbrother')){
//     lien.addEventListener('click',getApi,false)
// }
