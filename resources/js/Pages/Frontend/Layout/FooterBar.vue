<template>
    <v-footer color="transparent-darken-1 text-center d-flex flex-column" elevation="0">
        <div>
            <v-btn v-for="link in links" :key="link" color="black" variant="text" class="mx-4" rounded="xl">
                {{ link }}
            </v-btn>
        </div>
        <div class="pt-0">
            <v-img
                :src="`./storage/app_icon/${JSON.parse(store.app_logo)}`"
                :lazy-src="`./storage/app_icon/${JSON.parse(store.app_logo)}`"
                width="70"
                height="70"
                
            ></v-img>
            
        </div>
        <div>
            Phasellus feugiat arcu sapien, et iaculis ipsum elementum sit amet.
            Mauris cursus commodo interdum. Praesent ut risus eget metus luctus accumsan id ultrices nunc.
            Sed at orci sed massa consectetur dignissim a sit amet dui. Duis commodo vitae velit et faucibus.
            Morbi vehicula lacinia malesuada. Nulla placerat augue vel ipsum ultrices, cursus iaculis dui sollicitudin.
            Vestibulum eu ipsum vel diam elementum tempor vel ut orci. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
        </div>
        
        

        <div>
            {{ new Date().getFullYear() }} - {{store.app_name}}
            
        </div>

      
 
    </v-footer>
</template>

<script>
export default {
    data: () => ({
       store: [],
        links: [
            'Home',
            'About us',
            'Team',
            'Services',
            'Blog',
            'Contact Us',

        ],
    }),
    methods: {
        getStore(){
            axios.get('/store')
            .then((response) => {
                this.store.app_logo = JSON.parse(response.data.app_logo);
                this.store = response.data
            })
            .catch((response) => {
                return alert('Error: ' + response);
            })
        },
    },
    created(){
        this.getStore();
    }
}

</script>