import {createToast} from "mosha-vue-toastify";

class Validation{
    constructor(data, inputId, name) {
        this.data = data;
        this.errors = [];
        this.name = name
        this.inputId = inputId;
    }

    required() {
        if (!this.data) {
            if(typeof this.data === 'string'){
                if(this.data.trim() === ''){
                    this.errors.push('To pole jest wymagane');
                }
            }
        }
        return this;
    }

    minLength(length) {
        if (this.data.length < length) {
            this.errors.push(`Minimalna długość to ${length} znaków`);
        }
        return this;
    }

    isEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.data)) {
            this.errors.push('To nie jest poprawny adres email');
        }
        return this;
    }



    specialChars() {
        const htmlRegex = /[&<>"'/]/;
        if (htmlRegex.test(this.data)) {
            this.errors.push('To pole zawiera niedozwolone znaki');
        }
        return this;
    }

    isValid() {
        return this.errors.length === 0;
    }

    check() {
        if (!this.isValid()) {
            const inputElement = document.getElementById(this.inputId);
            createToast({
                    title: `Złe dane w polu ${this.name}.`,
                    description: this.errors.join(", ")
                },
                {
                    position: 'top-center',
                    showIcon: 'true',
                    type: 'danger',
                    transition: 'bounce',
                    showCloseButton: true,
                    swipeClose: true,
                })
            if (inputElement) {
                inputElement.classList.add("p-invalid")
                inputElement.classList.add('error'); // Dodaj klasę CSS w razie błędu
            }
        }
    }
}

export default Validation;