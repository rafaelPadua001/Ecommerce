<template>
    <v-row fluid>
        <v-col class="d-flex justify-center flex-column">
            <v-sheet class="py-2 px-2">
                <v-card class="mx-auto" elevation="2">
                    <v-card-text :elevation="10">
                        <v-row>
                            <v-col cols="auto">
                                <label>App bar color:</label>
                                
                            </v-col>
                            <v-col cols="auto">
                            
                                <v-color-picker v-model="color" class="ma-2" show-swatches swatches-max-height="50px" @click="selectColor(color)"></v-color-picker>
                                
                            </v-col>
                            <v-col cols="auto">
                                <v-app-bar :color="this.selectedColor" :elevation="2">
                                <template v-slot:prepend>
                                    <v-app-bar-nav-icon></v-app-bar-nav-icon>
                                </template>
                                
                                <v-app-bar-title>{{ store.app_name }}</v-app-bar-title>
                                </v-app-bar>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-text>
                        <v-divider></v-divider>
                        <v-row>
                            <v-col cols="auto">
                                <label>Cards Config</label>
                            </v-col>
                            
                            <v-col cols="auto" md="4">
                                <v-card class="mx-auto" elevation="0">
                                    <v-card-title>
                                        <v-card-toolbar>
                                           <v-toolbar-title>Card Configs</v-toolbar-title>
                                        </v-card-toolbar>
                                    </v-card-title>
                                    <v-divider></v-divider>
                                    <v-card-title>
                                        Card infos
                                    </v-card-title>

                                </v-card>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="auto">
                              
                                <label>Chips</label>
                           
                            </v-col>
                            <v-col cols="auto">
                                cips config
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
    Form Style here
</template>

<script>
export default {
    props: ['store'],
    data: () => ({
      color: '',
      selectedColor: false, 
    }),
    methods: {
        selectColor(item) {
            this.selectedColor = item;
            return selectedColor;
        }
    }
}
</script>