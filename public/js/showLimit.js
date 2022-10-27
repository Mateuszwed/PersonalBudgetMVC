async function showLimit(limit) {

    const windowWithInformation = document.querySelector('#showLimit');
    let nameOfCategory = document.querySelectorAll('.categorySelect');

    if (limit !== "0.00") {

        let sumOfExpense = getSumOfExpenseOfCategory();
        console.log(sumOfExpense);

        sumOfExpense.then((value) => {


            let expense;

            if (value != null) {
                expense = value;

            }
            else {
                expense = 0;

            }

            showLimitInformation(limit, expense, nameOfCategory);
            showDifferenceAfterEnteredAmount(limit, expense);


        })



    } else {

        windowWithInformation.classList.add('d-none');
    }


}

const getSumOfExpenseOfCategory = async () => {

    let categoryName = document.querySelector('.categorySelect').value;

    const firstDay = getFirstDay();
    const lastDay = getLastDay();
    console.log(firstDay);
    console.log(lastDay);

    let data = {
        start: firstDay,
        end: lastDay
    };

    const options = {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    };

    let response = await fetch('/Expense/getExpenses', options);
    const data2 = await response.json();

    console.log(data2);

    for (let i = 0; i < data2.length; i++) {
        if (categoryName == data2[i].name) {

            categoryName = data2[i].name;
            console.log(categoryName);

            sumOfExpense = data2[i].sum;
            console.log(sumOfExpense);

            getTotalExpense(sumOfExpense);
            console.log(sumOfExpense);
            return sumOfExpense;

        }
    }
}

    const getDateFromInput = () => {

        let dateFromInput = document.getElementById('theDate').value;

        let day = dateFromInput.slice(8);
        let month = dateFromInput.slice(6, 7);
        let year = dateFromInput.slice(0, 4);

        let date = new Date(year, month - 1, day);

        return date;
    }

    const getFirstDay = () => {

        let dateFromInput = getDateFromInput();
        dateFromInput.setDate(2);
        const firstDay = dateFromInput.toISOString().slice(0, 10);

        return firstDay;
    };


    const getLastDay = () => {

        let dateFromInput = getDateFromInput();
        dateFromInput.setDate(2);
        dateFromInput.setMonth(dateFromInput.getMonth() + 1, 1);
        const lastDay = dateFromInput.toISOString().slice(0, 10);

        return lastDay;
}

const showLimitForSelectedCategory = async () => {
    console.log('Limit');
    let categoryName = document.querySelector('.categorySelect').value;
    console.log(categoryName);
    let categoryId;

    let response = await fetch('/api/expenses');
    const data = await response.json()

    console.log(data);

    for (let i = 0; i < data.length; i++) {
        if (categoryName == data[i].name) {

            categoryId = data[i].id;
            console.log(typeof (categoryId));
            console.log(categoryId);

            limitOfCategory = data[i].categoryLimit;
            console.log(limitOfCategory);

            showLimit(limitOfCategory);

        }

    }

}