<template>
    <v-footer color="transparent-darken-1 text-center d-flex flex-column" elevation="0">
        <div>
            <v-btn v-for="link in links" :key="link" color="black" variant="text" class="mx-4" rounded="xl">
                {{ link }}
            </v-btn>
        </div>
        <div>
            <v-btn v-for="icon in icons" :key="icon" class="mx-4" :icon="icon" variant="text"></v-btn>
        </div>
        <div>
            <v-row no-gutters>
                <v-col>
                    Phasellus feugiat arcu sapien, et iaculis ipsum elementum sit amet.
                    Mauris cursus commodo interdum. Praesent ut risus eget metus luctus accumsan id ultrices nunc.
                    Sed at orci sed massa consectetur dignissim a sit amet dui. Duis commodo vitae velit et faucibus.
                    Morbi vehicula lacinia malesuada. Nulla placerat augue vel ipsum ultrices, cursus iaculis dui
                    sollicitudin.
                    Vestibulum eu ipsum vel diam elementum tempor vel ut orci. Orci varius natoque penatibus et magnis dis
                    parturient montes, nascetur ridiculus mus.
                </v-col>
            </v-row>
        </div>
       
        <v-divider></v-divider>
        <div>
            {{ new Date().getFullYear() }} - <strong>{{ store.app_name }}</strong>
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
        icons: [
            'fa-brands fa-facebook-f',
            'fa-brands fa-twitter',
            //'mdi-linkedin',
            'fa-brands fa-instagram',
        ],
    }),
    methods: {
        getStore() {
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
    created() {
        this.getStore();
    }
}

</script>