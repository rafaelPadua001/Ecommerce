<template>
    <v-row fluid>
        <v-col class="d-flex justify-center flex-column">
            <v-sheet class="py-2 px-2">
                <v-card class="mx-auto" elevation="2">
                    <v-card-text :elevation="10">
                        <v-row>
                            <v-col cols="auto">
                                <h4>App bar color:</h4>

                            </v-col>

                        </v-row>
                        <v-row>
                            <v-col cols="auto">

                                <v-color-picker v-model="color" class="ma-2" show-swatches swatches-max-height="50px"
                                    @click="selectColor(color)"></v-color-picker>

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
                                <h4>Cards Config</h4>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="auto">

                                <label>Chips</label>

                            </v-col>
                            <v-col cols="auto">
                                <v-color-picker v-model="chip_color" class="ma-2" show-swatches swatches-max-height="50px"
                                    @click="chipColor(chip_color)"></v-color-picker>
                            </v-col>

                            <v-col cols="auto" md="4">
                                <v-card class="mx-auto elevation-1" v-bind="props">
                                    <v-card-title>
                                        <v-toolbar class="bg-transparent">
                                            <template v-slot:append>
                                                <v-btn-group class="float-end">
                                                    <v-btn icon size="x-small">
                                                        <v-icon icon="fa-regular fa-heart fa-2xs" class="bg-transparent"
                                                            @click="like()"></v-icon>

                                                    </v-btn>
                                                    <v-btn icon size="x-small">
                                                        <v-icon icon="fa-solid fa-share-nodes fa-2xs"></v-icon>

                                                    </v-btn>
                                                </v-btn-group>
                                            </template>
                                        </v-toolbar>

                                    </v-card-title>
                                    <div>
                                        <v-img aspect-ratio="1/1" src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
                                            lazy-src="https://cdn.vuetifyjs.com/images/cards/cooking.png" :height="300"
                                            :width="400" cover>

                                            <div class="d-flex justify-end text-center elevation-0">
                                                <v-chip class="ma-2" label :color="this.selectedChipColor"
                                                    variant="elevated">
                                                    - 10%
                                                </v-chip>

                                            </div>

                                            <template>
                                                <div class="d-flex align-center justify-center fill-height">
                                                    <v-progress-circular color="grey-lighten-4">
                                                    </v-progress-circular>
                                                </div>
                                            </template>

                                        </v-img>
                                    </div>


                                    <!-- <v-card-text>
            <v-row fluid>
                <v-col cols="auto">
                    <p class="text-h5">{{ product.name }}</p>

                </v-col>
            </v-row>
            <v-row fluid>
                <v-col cols="auto">
                    <p>{{ product.description }}</p>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-btn-group>
                    <v-btn class="me-2" size="x-small" variant="outlined" color="orange"
                        v-if="product.slug">
                        {{ product.slug }}
                    </v-btn>
                    <v-btn v-if="product.discount_id" class="me-2" size="x-small"
                        variant="outlined" color="green">
                        {{ product.discount_percentage * 100 }}% off
                    </v-btn>
                </v-btn-group>
            </v-row>

            <v-row no-gutters>
                <v-col cols="auto" md="6" sm="4">
                    <div v-if="!product.discount_id">
                        <p>
                            <strong>R$:</strong>
                            {{ product.price }}
                        </p>
                    </div>
                    <div v-else>
                        <div>
                            <div>
                                <p>
                                    <strong>R$:</strong>
                                    {{ (product.price - (product.price *
                                        product.discount_percentage)).toFixed(2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </v-col>
                <v-col col="auto" md="6" sm="6">
                    <p color="red" v-if="product.stock_quantity >= 1">
                        <strong>Qtd:</strong> {{ product.stock_quantity }}
                    </p>
                    <p color="red" v-if="product.stock_quantity === 0">
                        <strong> Fora de Estoque </strong>
                    </p>
                </v-col>
               <v-col col="auto" md="5" sm="4">
<strong>Solds:</strong> 100
</v-col>
            </v-row>

        </v-card-text>
        <v-expand-transition>
            <div v-if="isHovering"
                class="d-flex transition-fast-in-fast-out bg-grey-darken-3 v-card-menu--reveal text-h2">
                <v-card-actions>
                    <v-btn @click="buy(product)" block>
                        <v-icon icon="fas fa-eye"></v-icon>
                        <v-tooltip activator="parent" location="end">preview</v-tooltip>
                    </v-btn>
                </v-card-actions>
            </div>
        </v-expand-transition>-->
                                </v-card>
                            </v-col>
                        </v-row>

                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row>
                            <v-col cols="auto">
                                <h4>Banners</h4>

                            </v-col>

                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner1" label="Main Banner"
                                    @change="previewBanner1"
                                >
                                </v-file-input>
                            </v-col>
                            <v-col>
                                {{ banner1 }}
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner2" label="Banner 1"></v-file-input>
                            </v-col>
                            <v-col>
                                {{ banner2 }}
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner3" label="Banner 2"></v-file-input>
                            </v-col>
                            <v-col>
                                {{ banner3 }}
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['store'],
    data: () => ({
        color: '',
        selectedColor: false,
        chip_color: '',
        selectedChipColor: false,
        banner1: [],
        banner2: [],
        banner3: [],
    }),
    watch: {
        previewBanner1(newUrl, oldUrl) {
            console.log(`Banner1 foi alterado: ${oldUrl} para ${newUrl}`);
            return true;
        }
    },
    methods: {
        selectColor(item) {
            this.selectedColor = item;
            return selectedColor;
        },
        chipColor(item) {
            this.selectedChipColor = item;
            return this.selectedChipColor;
        },
        handleFiles() {
            this.$refs.banner1[0];
        },
        previewBanner1(event) {
            const files = event.target.files;
           console.log(files);
            if (files) {
                for (let i = 0; i < files.length; i++) {
          const reader = new FileReader();
          const file = files[i];

          reader.onload = (e) => {
            this.banner1.push({
              src: e.target.result,
              name: file.name,
            });
          };
         reader.readAsDataURL(file);
        }
               
            }


        }
    }
}
</script>