export default () => ({

    imageChanged(input, image){

        const [ file ] = input.files;

        image.src = URL.createObjectURL(file)
    }

})