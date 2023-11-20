import Swal from 'sweetalert2'

const updateQueryStringParameter = (uri, key, value) => {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
      return uri + separator + key + "=" + value;
    }
  }

export default () => ({
    limit: 10,
 
    limiterChanged(value){

        let location = updateQueryStringParameter(window.location.href, 'limit', value);
        location = updateQueryStringParameter(location, 'page', 1);

        window.location.href = location
    
    },

    deleteElement(url, text = 'Are you sure want to delete this item?'){

        Swal.fire({
            title: 'Confirm',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons : true
        }).then((result) => {
            if (result.isConfirmed) {
                window.axios.delete(url).then(r => {
                    window.location.reload()
                }).catch(({ response }) => {

                    if(response.data.message){

                        Swal.fire({
                            title: 'Warning',
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK',
                        })
    
                    }

                })
            }
        })
        
    },

    sort(sortBy, sortType){
        let location = updateQueryStringParameter(window.location.href, 'sortBy', sortBy);
        location = updateQueryStringParameter(location, 'sortType', sortType);

        window.location.href = location
    },

    searchSortChanged(val){
        const parts = val.split('-')
        this.sort(parts[0], parts[1])
    }
})