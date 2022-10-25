window.onload = manageModal();

function manageModal() {


	$(document).on('click', '#deleteButton', function () {

		$('#deleteModal input[name="categoryId"]').val($(this).attr('data-category-id'));
		console.log($(this).attr('data-category-id'));

	});

	$(document).on('click', '#limitButton', function () {

		$('#limitModal input[name="limit"]').val($(this).attr('data-category-limit'));
		$('#limitModal input[name="categoryId"]').val($(this).attr('data-category-id'));
		console.log($(this).attr('data-category-id'));
		console.log($(this).attr('data-category-limit'));

	});

	$(document).on('click', '#editButton', function () {

		$('#editModal input[name="newName"]').val($(this).attr('data-category-name'));
		$('#editModal input[name="categoryId"]').val($(this).attr('data-category-id'));
		console.log($(this).attr('data-category-id'));

	});

}