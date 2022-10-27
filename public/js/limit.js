let categorySelect = document.getElementById('category');
let dateInput = document.getElementById('theDate');
let amountInput = document.getElementById('inputAmount');
const show = document.querySelector('#showLimit');
let limitForCategory = document.getElementById('limitForCategory');
let monthlySum = document.getElementById('monthlySum');
let infoAboutLimit = document.getElementById('info');


const checkCategory = async () => {

    let categoryId = categorySelect.value;
    let date = getDateFromInput();
    let categoryLimit;

    const limit = await getLimitForCategory(categoryId).then(data => {
        categoryLimit = Number(data);
    });

    if (categoryLimit === 0) {
        divLimit(show, false);
    } else {
        divLimit(show, true);
        checkLimit(categoryLimit, categoryId, date);
    }
};


const checkLimit = async (categoryLimit, categoryId, date) => {

    const jsoNData = await getSumOfExpensesForSelectedMonth(categoryId, date).then(data => {
        sumOfExpensesMonthly = data;
    });
    let getAmountInput = amountInput.value;

    console.log(sumOfExpensesMonthly);
    if (getAmountInput === null) {
        getAmountInput = 0;
    }


    renderOnDom(categoryLimit, sumOfExpensesMonthly, getAmountInput);
};


const getLimitForCategory = async (id) => {

    try {
        const response = await fetch(`/expenses/getCategoryLimit/${id}`);
        const data = await response.json();

        return data[0].limit_amount;
    }   
    catch (error) {
        console.error(`Error: ${error}`);
    }
};


const getSumOfExpensesForSelectedMonth = async (categoryID, date) => {

    let firstDateInput = getFirstDay(date);
    let lastDateInput = getLastDay(date);


    try {
        const response = await fetch(`getExpenses/${categoryID}/${firstDateInput}/${lastDateInput}`);
        const data = await response.json();

        if (data.length === 0) {
            return 0
        } else {
            console.log(data[0].sum)
            return data[0].sum;
        }
    }
    catch (error) {
        console.error(`Error: ${error}`);
    }
    
};

const calculateLimits = async (limit, amount) => {

    diferent = limit - amount;

    return diferent;
};

function divLimit(element, show) {
    if (show === false) {
        element.classList.add('hide');
    } else {
        element.classList.remove('hide');
    }
}


const renderOnDom = (categoryLimit, sumOfExpensesMonthly, getAmountInput) => {

    limitForCategory.textContent = `${categoryLimit}`;
    monthlySum.textContent = `${sumOfExpensesMonthly}`;
    let restAmount2 = categoryLimit - sumOfExpensesMonthly;
    let restAmount = +restAmount2 - +getAmountInput;
    let expensesAndInputAmount = +sumOfExpensesMonthly + +getAmountInput;

    if (categoryLimit < +sumOfExpensesMonthly + +getAmountInput) {
        infoAboutLimit.style.color = "#ff0505";
        infoAboutLimit.textContent = ` ${Math.abs(expensesAndInputAmount.toFixed(2))}`;

    } else {
        infoAboutLimit.style.color = "greenyellow";
        infoAboutLimit.textContent = ` ${Math.abs(expensesAndInputAmount.toFixed(2))}`;
    }

};


const getDateFromInput = () => {

    let dateFromInput = dateInput.value;

    let day = dateFromInput.slice(8,10);
    let month = dateFromInput.slice(5, 7);
    let year = dateFromInput.slice(0, 4);


    let date = new Date(year, month-1, day);

    return date;
}


const getFirstDay = (date) => {

    let dateFromInput = date;
    dateFromInput.setDate(2);
    const firstDay = dateFromInput.toISOString().slice(0, 10);

    return firstDay;
};

const getLastDay = (date) => {

    let dateFromInput = date;
    dateFromInput.setDate(2);
    dateFromInput.setMonth(dateFromInput.getMonth() + 1, 1);
    const lastDay = dateFromInput.toISOString().slice(0, 10);

    return lastDay;
}



categorySelect.addEventListener('change', async () => {
    await checkCategory();
})

dateInput.addEventListener('change', async () => {
    await checkCategory();
})

amountInput.addEventListener('change', async () => {
    await checkCategory();
})

document.onload = checkCategory();