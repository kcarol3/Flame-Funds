import {createToast} from "mosha-vue-toastify";

class Validation{
    constructor(data, inputId, name) {
        this.data = data;
        this.errors = [];
        this.name = name
        this.inputId = inputId;
    }

    /**
     * Sprawdza czy pole nie jest puste.
     * @returns {Validation}
     */
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

    /**
     * Sprawdza czy hasło ma minimum 6 znaków, nie więcej niż 16, zawiera cyfrę oraz znak specjalny.
     * @returns {Validation}
     */
    isPassword(){
        const passwordRegex= /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
        if (!passwordRegex.test(this.data)){
            this.errors.push('To nie jest poprawne hasło');
        }
        return this;
    }

    /**
     * Sprawdza czy tekst ma minimalną podaną ilość znaków.
     * @param length
     * @returns {Validation}
     */
    minLength(length) {
        if (this.data.length < length) {
            this.errors.push(`Minimalna długość to ${length} znaków`);
        }
        return this;
    }

    /**
     * Sprawdza czy tekst ma mniej niż maksymalna podaną ilość znaków.
     * @param length
     * @returns {Validation}
     */
    maxLength(length) {
        if (this.data.length > length) {
            this.errors.push(`Minimalna długość to ${length} znaków`);
        }
        return this;
    }

    /**
     * Sprawdza czy w polu jest wpisany poprawny email.
     * @returns {Validation}
     */
    isEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.data)) {
            this.errors.push('To nie jest poprawny adres email');
        }
        return this;
    }

    /**
     * Sprawdza czy w polu są wpisane znaki specjalne html.
     * @returns {Validation}
     */
    specialChars() {
        const htmlRegex = /[&<>"'/]/;
        if (htmlRegex.test(this.data)) {
            this.errors.push('To pole zawiera niedozwolone znaki');
        }
        return this;
    }

    /**
     * Sprawdza czy w polu jest taka wartość jak "text".
     * @param text
     * @return {Validation}
     */
    sameAs(text){
        if(this.data !== text){
            this.errors.push('Pola muszą być takie same');
        }
        return this;
    }

    /**
     * Sprawdza czy nie wystąpiły błędy walidacji. W razie braku błędów zwraca true.
     * @returns {boolean}
     */
    isValid() {
        return this.errors.length === 0;
    }

    /**
     * Sprawdza czy nie ma błędów w konkretnym polu. Jeśli są, wyświetla odpowiednie komunikaty.
     * @return {Validation}
     */
    check() {
        const inputElement = document.getElementById(this.inputId);
        if (!this.isValid()) {
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
                inputElement.classList.remove('success');
                inputElement.classList.add("p-invalid");
                inputElement.classList.add('error');
            }
        } else {
            if (inputElement) {
                inputElement.classList.remove('error');
                inputElement.classList.remove("p-invalid");

                inputElement.classList.add('success');
            }
        }
        return this;
    }
}

export default Validation;