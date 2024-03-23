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
                            <v-col cols="6" v-if="Object.keys(style).length == 0">
                                <v-sheet>
                                    <v-card :color="this.selectedColor">
                                        <template v-slot:prepend>
                                            <v-app-bar-nav-icon></v-app-bar-nav-icon>
                                        </template>
                                        <v-card-text></v-card-text>
                                    </v-card>
                                </v-sheet>

                            </v-col>
                            <v-col v-else cols="4">
                                <v-sheet>
                                    <v-card :color="JSON.parse(style.colors)">
                                        <template v-slot:prepend>
                                            <v-app-bar-nav-icon></v-app-bar-nav-icon>
                                            {{ store.name }}
                                        </template>
                                        <v-card-text></v-card-text>
                                    </v-card>
                                </v-sheet>

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
                                <v-color-picker v-model="chip_color" class="ma-2" show-swatches
                                    swatches-max-height="50px" @click="chipColor(chip_color)"></v-color-picker>
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
                                        <v-img aspect-ratio="1/1"
                                            src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
                                            lazy-src="https://cdn.vuetifyjs.com/images/cards/cooking.png" :height="300"
                                            :width="400" cover>

                                            <div class="d-flex justify-end text-center elevation-0"
                                                v-if="Object.keys(style).length == 0">
                                                <v-chip class="ma-2" label :color="this.selectedChipColor"
                                                    variant="elevated">
                                                    - 10%
                                                </v-chip>


                                            </div>

                                            <div class="d-flex justify-end text-center elevation-0" v-else>

                                                <v-chip class="ma-2" label :color="JSON.parse(style.chip_color)"
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
                                <v-file-input v-model="banner1" label="Main Banner" @change="previewBanner1">
                                </v-file-input>
                            </v-col>

                            <v-col v-if="Object.keys(style).length == 0" v-for="banner in banner1" :key="banner.id"
                                cols="2">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

                                </v-img>
                            </v-col>
                            <v-col v-else cols="auto">

                                <v-img v-if="style.banner_image"
                                    :src="`./storage/Banners/${JSON.parse(style.banner_image)}`"
                                    :lazy-src="`./storage/Banners/${JSON.parse(style.banner_image)}`" cover
                                    :width="100">

                                </v-img>
                                <v-img v-else-if="style[0]['banner1'][1]['name']"
                                    :src="`./storage/Banners/${style[0]['banner1'][1]['name']}`"
                                    :lazy-src="`./storage/Banners/${style[0]['banner1'][1]['name']}`" cover
                                    :width="100">

                                </v-img>
                            </v-col>
                            <v-col v-for="banner in banner1" :key="banner.id" cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>
                        </v-row>

                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row>
                            <v-col cols="auto">
                                <h4>Carrousel:</h4>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner2" label="Image 1" @change="previewBanner2"></v-file-input>
                            </v-col>
                            <v-col v-if="Object.keys(style).length == 0" v-for="banner in banner2" :key="banner.id"
                                cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>

                            </v-col>
                            <v-col v-else-if="style.images">
                                <div v-for="(img, index) in JSON.parse(style.images)" :key="index" cols="auto">

                                    <v-img v-if="index === 0" :src="`./storage/Carrousel/${img}`"
                                        :lazy-src="`./storage/Carrousel/${img}`" :alt="`${img}`" cover :width="100">
                                    </v-img>
                                </div>
                            </v-col>

                            <v-col v-for="banner in banner2" :key="banner.id" cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner3" label="Image 2" @change="previewBanner3"></v-file-input>
                            </v-col>
                            <v-col v-if="Object.keys(style).length === 0" v-for="banner in banner3" :key="banner.id"
                                cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>

                            <v-col v-else v-for="(st, index) in JSON.parse(style.images)" :key="index" cols="auto">

                                <v-img v-if="index === 1" :src="`./storage/Carrousel/${st}`"
                                    :lazy-src="`./storage/Carrousel/${st}`" :alt="st" cover :width="100">

                                </v-img>

                            </v-col>
                            <v-col v-for="banner in banner3" :key="banner.id" cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner4" label="Image 3" @change="previewBanner4"></v-file-input>
                            </v-col>
                            <v-col v-if="Object.keys(style).length === 0" v-for="banner in banner4" :key="banner.id"
                                cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>
                            <v-col v-else v-for="(st, index) in JSON.parse(style.images)" :key="index" cols="auto">

                                <v-img v-if="index === 2" :src="`./storage/Carrousel/${st}`"
                                    :lazy-src="`./storage/Carrousel/${st}`" :alt="st" cover :width="100">

                                </v-img>

                            </v-col>
                            <v-col v-for="banner in banner4" :key="banner.id" cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner5" label="Image 4" @change="previewBanner5"></v-file-input>
                            </v-col>
                            <v-col v-if="Object.keys(style).length === 0" v-for="banner in banner5" :key="banner.id"
                                cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

                                </v-img>
                            </v-col>
                            <v-col v-else v-for="(st, index) in JSON.parse(style.images)" :key="index" cols="auto">

                                <v-img v-if="index === 3" :src="`./storage/Carrousel/${st}`"
                                    :lazy-src="`./storage/Carrousel/${st}`" :alt="st" cover :width="100">

                                </v-img>

                            </v-col>
                            <v-col v-for="banner in banner5" :key="banner.id" cols="auto">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="100">

                                </v-img>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row>
                            <v-col class="d-flex justify-center flex-column" cols="auto">
                                <h4>Footer:</h4>
                            </v-col>
                        </v-row>
                        <v-row>
                            <FooterForm @submitFormData="submitFormData" />
                            <p>Dados recebidos do componente filho: {{ formData }}</p>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn-group>
                            <v-btn class="me-2" variant="text" size="lg" color="primary" @click="save"
                                v-if="Object.keys(style).length == 0">
                                Save
                            </v-btn>
                            <v-btn class="me-2" variant="text" size="lg" color="primary" @click="update(style)" v-else>
                                Update
                            </v-btn>
                            <v-btn class="me-2" variant="text" size="lg" color="error" @click="openDeleteDialog(style)"
                                v-if="Object.keys(style).length >= 1">
                                Remove
                            </v-btn>
                            <v-btn class="me-2" variant="text" size="lg" color="error">
                                Clear
                            </v-btn>
                        </v-btn-group>
                    </v-card-actions>
                </v-card>

                <div>
                    <DeleteStyleDialog v-model="deleteDialog" :style="this.style" @remove-style="deleteItemConfirm"
                        @close-dialog="deleteDialog = false" />
                </div>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';
import DeleteStyleDialog from './deleteStyleDialog.vue';
import FooterForm from './FooterForm.vue';
export default {
    props: ['store'],
    components: {
        DeleteStyleDialog,
        FooterForm
    },
    data: () => ({
        color: '',
        selectedColor: false,
        chip_color: '',
        selectedChipColor: false,
        banner1: [],
        banner2: [],
        banner3: [],
        banner4: [],
        banner5: [],
        style: [],
        formData: null,
        footerLinks: [],
        footerIcons: [],
        footerText: '',
        footerColor: [],
        editedItem: [],
        editedIndex: -1,
        deleteDialog: false,
    }),
    watch: {
        previewBanner1(newUrl, oldUrl) {
            return true;
        },
        previewBanner2(newUrl, oldUrl) {
            return true;
        },
        previewBanner3(newUrl, oldUrl) {
            return true;
        },
        previewBanner4(newUrl, oldUrl) {
            return true;
        },
        previewBanner5(newUrl, oldUrl) {
            return true;
        }
    },
    methods: {
        getStyle() {
            axios.get(`/store/style/getStyle/${this.store.id}`)
                .then((response) => {
                    return this.style = response.data.original;
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                })
        },
        selectColor(item) {
            this.selectedColor = item;
            return this.selectedColor;
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
        },
        previewBanner2(event) {
            const files = event.target.files;
            if (files) {
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    const file = files[i];

                    reader.onload = (e) => {
                        this.banner2.push({
                            src: e.target.result,
                            name: file.name,
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }
        },
        previewBanner3(event) {
            const files = event.target.files;
            if (files) {
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    const file = files[i];

                    reader.onload = (e) => {
                        this.banner3.push({
                            src: e.target.result,
                            name: file.name,
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }
        },
        previewBanner4(event) {
            const files = event.target.files;
            if (files) {
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    const file = files[i];

                    reader.onload = (e) => {
                        this.banner4.push({
                            src: e.target.result,
                            name: file.name,
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }
        },
        previewBanner5(event) {
            const files = event.target.files;
            if (files) {
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    const file = files[i];

                    reader.onload = (e) => {
                        this.banner5.push({
                            src: e.target.result,
                            name: file.name,
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }
        },
        submitFormData(formData) {
            this.footerLinks = formData.links;
            this.footerIcons = formData.socialMidiasIcon;
            this.footerText = formData.footerText;
            this.footerColor = formData.swatches;
        },
        update(item) {
            this.editedIndex = item.id;
            this.editedItem = Object.assign({}, item);
            this.save();
        },
        openDeleteDialog(item) {
            console.log(item[0]);
            this.editedItem = Object.assign({}, item);
            this.editedIndex = item.id;
            this.deleteDialog = true;
        },
        deleteItemConfirm(item) {
            return this.style = "";
        },
        save() {
            if (this.editedIndex != -1) {
                const itemId = this.editedItem.store_id;
                console.log(this.editedIndex);
                const data = {
                    colors: JSON.stringify(this.selectedColor),
                    chip_color: JSON.stringify(this.selectedChipColor),
                    banner1: this.banner1,
                    banner2: this.banner2,
                    banner3: this.banner3,
                    banner4: this.banner4,
                    banner5: this.banner5,
                    footerLinks: this.footerLinks,
                    footerIcons: this.footerIcons,
                    footerText: this.footerText,
                    footerColor: this.footerColor,
                    storeId: this.editedItem.store_id
                };
                axios.post(`/store/style/update/${itemId}`, data)
                    .then((response) => {
                        return this.style = Object.assign({}, response.data.original);
                    })
                    .catch((response) => {
                        return alert('Error' + response);
                    });


            }
            else {
                const data = {
                    appBarColor: this.selectedColor,
                    chipColor: this.selectedChipColor,
                    banner1: this.banner1,
                    banner2: this.banner2,
                    banner3: this.banner3,
                    banner4: this.banner4,
                    banner5: this.banner5,
                    footerLinks: this.footerLinks,
                    footerIcons: this.footerIcons,
                    footerText: this.footerText,
                    footerColor: this.footerColor,
                    storeId: this.editedItem.store_id
                };
                axios.post(`/store/style/create`, data)
                    .then((response) => {
                        return this.style = response.data.original;
                    })
                    .catch((response) => {
                        return alert('Error: ' + response);
                    });
            }
        }
    },
    mounted() {
        this.getStyle();
    }
}
</script>