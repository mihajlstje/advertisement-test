export default () => ({

    text: 'Select category',
 
    get buttonText() {

        const selectedCategory = document.querySelector('input[name="category_id"]:checked')

        if(selectedCategory){

            this.setText(selectedCategory)

        }

        return this.text
    },

    setText(selectedCategoryInput){

        this.text = selectedCategoryInput.nextElementSibling.textContent

    }
})