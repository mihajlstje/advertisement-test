import Swal from 'sweetalert2'

export default () => ({
    limit: 10,

    banCustomer(customer){

        let text, button;

        if(customer.active){
            text = 'Are you sure want to ban this customer? It means user will not be able to login and post new adverts from now on.';
            button = 'Yes, ban'
        } 
        else{
            text = 'Are you sure want to unban this customer and make him able to login & post adverts again?';
            button = 'Yes, unban'
        } 

        const url = '/customer/'+customer.id

        Swal.fire({
            title: 'Confirm',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: button,
            cancelButtonText: 'Cancel',
            reverseButtons : true
        }).then((result) => {
            if (result.isConfirmed) {
                window.axios.patch(url).then(r => {
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
        
    }

    
})