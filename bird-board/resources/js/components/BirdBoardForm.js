import {AxiosInstance as axios} from "axios";

class BirdBoardForm {
    constructor(data) {
        this.originalData = JSON.parse(JSON.stringify(data));

        Object.assign(this, data);

        this.errors = {};
        this.submitted = false;
    }

    post(endpoint) {
        this.submit(endpoint, 'post');
    }

    patch(endpoint) {
        this.submit(endpoint, 'patch');
    }

    delete(endpoint) {
        this.submit(endpoint, 'delete');
    }

    submit(endpoint, requestType = 'post') {
        return axios[requestType](endpoint, this.data())
            .then(this.onSuccess.bind(this))
            .catch(this.onFail.bind(this));
    }

    data() {
        return Object.keys(this.originalData).reduce((data, attribute) => {
            data[attribute] = this[attribute];

            return data;
        }, {})
    }

    onSuccess(response) {
        this.submitted = true;
        this.errors = {};

        return response;
    }

    onFail(error) {
        this.errors = error.response.data.errors;
        this.submitted = false;

        throw error;
    }

    reset() {
        Object.assign(this, this.originalData);
    }
}

export default BirdBoardForm;
