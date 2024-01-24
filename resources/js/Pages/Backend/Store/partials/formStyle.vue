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
                            <v-col v-for="banner in banner1" :key="banner.id" cols="2">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

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
                                <v-file-input v-model="banner2" label="Banner 2" @change="previewBanner2"></v-file-input>
                            </v-col>
                            <v-col v-for="banner in banner2" :key="banner.id" cols="2">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

                                </v-img>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner3" label="Banner 2" @change="previewBanner3"></v-file-input>
                            </v-col>
                            <v-col v-for="banner in banner3" :key="banner.id" cols="2">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

                                </v-img>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner4" label="Banner 2" @change="previewBanner4"></v-file-input>
                            </v-col>
                            <v-col v-for="banner in banner4" :key="banner.id" cols="2">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

                                </v-img>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-file-input v-model="banner5" label="Banner 2" @change="previewBanner5"></v-file-input>
                            </v-col>
                            <v-col v-for="banner in banner5" :key="banner.id" cols="2">
                                <v-img :src="banner.src" :lazy-src="banner.src" :alt="banner.src" cover :width="200">

                                </v-img>
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn-group>
                            <v-btn class="me-2" variant="text" size="lg" color="primary" @click="save">
                                Save
                            </v-btn>
                            <v-btn class="me-2" variant="text" size="lg" color="error">
                                Cancel
                            </v-btn>
                        </v-btn-group>
                    </v-card-actions>
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
        banner4: [],
        banner5: [],
        style: [],
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
        save() {
            const data = {
                appBarColor: this.selectedColor,
                chipColor: this.selectedChipColor,
                banner1: this.banner1,
                banner2: this.banner2,
                banner3: this.banner3,
                banner4: this.banner4,
                banner5: this.banner5,
            };
            axios.post(`/store/style/create`, data)
                .then((response) => {
                    this.style.push(response.data);
                    console.log(response.data);
                    return console.log(this.style);
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
            console.log(data);
        }
    }
}
</script>