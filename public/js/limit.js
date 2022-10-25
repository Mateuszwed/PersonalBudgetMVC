let categorySelect = document.getElementById('category');
let dateInput = document.getElementById('theDate');



const checkCategory = async () => {

    let category = categorySelect.value;
    let date = dateInput.value;
    let isLimit = await checkLimit(category);

    if (isLimit === "1") {

        let limit = await getLimitForCategory(category);
        console.log(limit);
    }
    else {
        console.log("Brak limitu")
    }

};

const checkLimit = async (id) => {
    try {
        const response = await fetch(`/expenses/checkLimit/${id}`);
        const data = await response.json();
        return data[0].is_limit;
    }
    catch {


    }

};

const getLimitForCategory = async (id) => {
    try {

        const response = await fetch(`/expenses/getLimitForCategory/${id}`);
        const data = await response.json();
        return data[0].limit_amount;
    }
    catch (error) {
        console.error("Error", error);
    }

};

const getSumOfExpensesForSelectedMonth = async () => {

};

const calculateLimits = () => {


};

const renderOnDom = () => {

};



checkCategory();

