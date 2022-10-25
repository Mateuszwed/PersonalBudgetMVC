let category = document.getElementById('category');

const checkCategory = async () => {

    let input = document.getElementById("limit");
    let checkBox = document.getElementById("limitCheck");

    let categoryId = category.value;

    console.log(categoryId);

    let is_limit = await checkIsLimit(categoryId);

    let limit = await getLimit(categoryId);
    console.log(limit);


    if (is_limit === 1) {
        console.log("JEST");
        checkBox.checked = true;
        input.style.display = "block";
    } else {
        checkBox.checked = false;
        console.log("BRAK");
        input.style.display = "none";
        return 0;
    }

};

function getCategoryId() {

    let categoryId = category.value;
    return categoryId;

}

const checkIsLimit = async (id) => {

    try {

        const response = await fetch(`/expenses/checkIsLimit/${id}`);
        const data = await response.json();
        return data[0].is_limit;
    }
    catch (error) {

        console.log("Error", error);
    }
};

const getLimit = async (id) => {

    try {

        const response = await fetch(`/expenses/getLimit/${id}`);
        const data = await response.json();
        return data[0].limit_amount;
    }
    catch (error) {

        console.log("Error", error);
    }
};




function showLimitInput() {

    let checkBox = document.getElementById("limitCheck");
    let input = document.getElementById("limit");

    if (checkBox.checked == true) {
        input.style.display = "block";
        addLimit(getCategoryId(), 1);

    } else {
        input.style.display = "none";
    }

}

checkCategory();