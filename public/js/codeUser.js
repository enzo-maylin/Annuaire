let inputField = document.getElementById('user_code');
if(inputField === null) inputField = document.getElementById('profil_code');

let button = document.getElementById('buttonSignUp');
if(button === null) button = document.getElementById('profil_modifier');
inputField.addEventListener('input', async function () {
    let value = inputField.value;

    let URL = Routing.generate('check_code', {"code": value});

    const response = await fetch(URL, {method: "POST"});

    if (response.status === 200) {
        this.classList.remove("border-warning");
        this.classList.add("border-success");
        button.classList.remove("disabled");
    }
    else {
        this.classList.remove("border-success");
        this.classList.add("border-warning");
        button.classList.add("disabled");
    }
});

