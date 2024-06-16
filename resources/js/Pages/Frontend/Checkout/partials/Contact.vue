<template>
    <v-row fluid>
        <v-col>
            <v-row>
                <v-col>
                    <p class="text-start text-body-1">Contact
                        Information
                    </p>
                    <v-divider></v-divider>
                    <v-spacer></v-spacer>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <v-form>
                        <v-text-field v-if="!customer.email" v-model="email" label="email" required :rules="emailRules"
                            prepend-icon="fas fa-envelope" placeholder="Email here...">

                        </v-text-field>
                        <v-text-field v-else v-model="customer.email" label="email" required :rules="emailRules"
                            prepend-icon="fas fa-envelope" placeholder="Email here...">

                        </v-text-field>
                    </v-form>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <v-checkbox label="Desejo receber ofertas e novidades" color="primary">

                    </v-checkbox>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="text-body-1 text-start">
                    <p>Shipping address</p>
                    <v-divider></v-divider>

                </v-col>
            </v-row>
            <div>
                <v-row fluid>
                    <v-col>

                        <ZipCodeField :selectProduct="this.carts" :quantity="this.quantity" :customer="this.customer"
                            @updateShippment="updateShippment" @updateAddressFields="updateAddressFields" />

                    </v-col>
                </v-row>

            </div>
            <v-row>
                <v-col v-if="Object.keys(this.address).length == 0">
                    <v-form>
                        <v-row>
                            <v-col>
                                <v-text-field v-model="customer.first_name" label="First Name">

                                </v-text-field>

                                {{ first_name }}
                            </v-col>
                            <v-col>
                                <v-text-field v-model="customer.last_name" label="Last Name">

                                </v-text-field>

                                {{ last_name }}
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-text-field v-model="billing_address.shippment_address" label="Address">

                                </v-text-field>


                            </v-col>

                        </v-row>

                        <v-row>
                            <v-col>
                                <v-text-field v-model="billing_address.shippment_complement"
                                    label="Complemento (opcional)">

                                </v-text-field>

                            </v-col>

                        </v-row>

                        <v-row fluid>
                            <v-col>
                                <v-text-field v-model="billing_address.shippment_city" label="Cidade">

                                </v-text-field>
                                {{ billing_address.shippment_city }}
                            </v-col>
                            <v-col cols="auto">

                                <v-select v-model="billing_address.select_uf" :items="ufs"
                                    :hint="`${billing_address.select_uf.state}, ${billing_address.select_uf.uf}`"
                                    item-title="uf" item.value="state" placeholder="UF" return-object single-line
                                    :placeholder="customerAddress.uf">

                                </v-select>
                                {{ billing_address.select_uf }}
                            </v-col>


                        </v-row>
                        <v-card-actions>
                            <v-row>
                                <v-col class="text-start">
                                    <v-btn type="text" :to="'/'">Return</v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col class="text-end">
                                    <v-btn @click="confirmNext()" color="primary">Confirmar</v-btn>
                                </v-col>
                            </v-row>


                        </v-card-actions>

                    </v-form>

                </v-col>
                <v-col v-else>
                    teste 1
                </v-col>
            </v-row>
        </v-col>

        <v-col v-if="confirm">
            <v-card class="d-flex justify-center flex-column" :width="500">

                <v-list :lines="false" density="compact" nav>

                    <v-list-item v-for="(item, index) in parsedProduct" :key="index" :value="item">
                        <v-toolbar color="transparent">
                            <template v-slot:append>
                                <v-btn icon size="x-small" @click="removeItemDialog(item)">
                                    <v-icon icon="fas fa-trash"></v-icon>
                                </v-btn>
                            </template>
                        </v-toolbar>

                        <v-row>
                            <v-col class="d-flex child-flex">
                               
                                <v-bagde color="red" :content="item.shippment_quantity" :value="true" bordered>
                                    <v-img v-if="item.images && item.images.length > 0" :width="4"
                                        :src="`./storage/products/${item.images[0]}`"
                                        :lazy-src="`./storage/products/${item.images[0]}`" aspect-ratio="16/9" cover
                                        :alt="item.images[0]" color="grey-lighten-2">
                                        <template v-slot:placeholder>
                                            <div class="d-flex justify-center fill-height flex-column">
                                                <v-progress-circular color="grey-lighten-4"
                                                    indeterminate></v-progress-circular>
                                            </div>
                                        </template>
                                    </v-img>
                                    
                                    <v-img v-else :width="4" :src="`/path/to/default/image.jpg`"
                                        :lazy-src="`/path/to/default/image.jpg`" aspect-ratio="16/9" cover>
                                        <template v-slot:placeholder>
                                            <div class="d-flex justify-center fill-height flex-column">
                                                <v-progress-circular color="grey-lighten-4"
                                                    indeterminate></v-progress-circular>
                                            </div>
                                        </template>
                                    </v-img>
                                </v-bagde>
                            </v-col>
                            <v-col cols="auto" class="d-flex flex-child text-body-2">
                                <strong>{{ item.name }}</strong>
                            </v-col>

                            <v-col cols="auto" class="d-flex flex-child text-body-2">
                                <!-- <strong>Product Price:</strong> -->
                                R$ {{ item.cart_item_price }}
                            </v-col>
                        </v-row>


                        <v-row no-gutters class="text-subtitle-2 justify-end">
                            <strong>Quantity:</strong> {{
                            item.quantity }}
                        </v-row>
                        <v-row no-gutters class="text-subtitle-2 justify-end">

                            <v-col cols="auto" v-for="(color, index) in item.cart_item_colors" :key="index">

                                <v-avatar :bg-color="color" :color="color" :width="1" rounded="10">
                                    <template v-slot:append>
                                        <!-- {{ parsedQuantityColors[index] }} -->
                                        <!-- <span>Available</span> -->
                                    </template>
                                </v-avatar>
                            </v-col>

                        </v-row>
                        <v-row no-gutters class="text-subtitle-2 justify-end">

                            <v-col cols="auto" v-for="(size, index) in item.cart_item_size" :key="index">

                                <v-avatar color="grey" :width="75" rounded="10">
                                    {{ size }}

                                </v-avatar>
                            </v-col>
                        </v-row>


                        <v-row fluid class="text-subtitle-2 justify-end" v-if="this.shippment.company">
                            <v-col cols="auto">

                                <p>shippment: {{ this.shippment.company.name }}</p>


                            </v-col>
                            <!-- <v-col cols="auto">
                                                                <p>R$ {{ item.shippment_price }}</p>


                                                            </v-col> -->

                        </v-row>

                        <!-- <v-row fluid class="text-subtitle-2 justify-end">
                                                            <v-col cols="auto">
                                                                <p>Total Delivery: R$ {{ item.delivery_price }}</p>
                                                            </v-col>
                                                            <v-col cols="auto">
                                                                <p>Total Price: R$ {{ item.total_price }}</p>
                                                            </v-col>
                                                        </v-row> -->
                        <v-divider></v-divider>
                        <v-spacer></v-spacer>
                    </v-list-item>

                </v-list>


            </v-card>

            <v-divider></v-divider>
            <v-spacer></v-spacer>
            <v-row>
                <v-col>
                    <v-input label="Discount coupon">
                        <template v-slot:append>
                            <v-btn color="grey" @click="couponApply">Apply</v-btn>
                        </template>
                        <v-text-field v-model="coupon" hide-details label="Gift Card or offer code"></v-text-field>
                    </v-input>
                </v-col>
            </v-row>

            <v-spacer></v-spacer>
            <v-divider></v-divider>
            <v-row no-gutters>
                <v-col class="text-start text-subtitle-2">
                    <p>Subtotal: </p>
                </v-col>
                <v-col class="text-end text-subtitle-2">
                    {{ formattedSubtotal }}
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col class="text-start text-subtitle-2">
                    <p>Shipping:</p>
                </v-col>
                <v-col class="text-end text-subtitle-2">
                    R$ {{ formatedShippmentPrice }}
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row fluid>
                <v-col class="text-start text-body-1">
                    <p><strong>Total:</strong></p>
                </v-col>
                <v-col class="text-end">
                    R$ {{ totalPrice }}
                </v-col>
            </v-row>


        </v-col>
    </v-row>
    <div>
                            <v-dialog v-model="removeDialog">
                                <v-card>
                                    <v-toolbar>
                                        <v-toolbar-title>{{ editedItem.name }}</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        Você deseja remover este Item ? {{ editedItem.cart_item_id }}
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-btn @click="closeRemoveItemDialog()">
                                            Close
                                        </v-btn>
                                        <v-btn color="error" @click="removeItemConfirm()">
                                            Confirm
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </div>

</template>

<script>
import ZipCodeField from '../../Layout/TextFields/ZipCode.vue';

export default {
    name: 'Contact',
    props: ['carts', 'customer', 'address', 'confirm', 'quantity'],
    components: {
        ZipCodeField
    },
    data: () => ({
        removeDialog: false,
        dataConfirm: false,
        billing_address: {
            shippment_address: '',
            shippment_complement: '',
            shippment_city: '',
            select_uf: { state: 'Distrito Federal', uf: 'DF' },
            zip_code: false,
            city: null,
            bairro: null,
        },
        email: '',
        emailRules: [
            v => !!v || 'O email é obrigatório',
            v => /.+@.+\..+/.test(v) || 'Email inválido',
        ],
        ufs: [
            { state: 'Distrito Federal', uf: 'DF' },
            { state: 'Goiás', uf: 'GO' },
            { state: 'Maranhão', uf: 'MA' },
        ],
        
        shippment: [],
    }),
    computed: {
        parsedProduct() {
            return this.carts.map((item) => {
                return {
                    ...item,
                    cart_item_colors: JSON.parse(item.cart_item_colors),
                    cart_item_size: JSON.parse(item.cart_item_size),
                    images: item.images ? JSON.parse(item.images) : [],
                }
            });
        },
        subtotal() {
            return this.carts.reduce((acc, item) => {
                return item.quantity * (acc + item.cart_item_price);
            }, 0)
        },
        formattedSubtotal() {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(this.subtotal);
        },
        formatedShippmentPrice() {
            const shippmentPrice = this.shippment && !isNaN(Number(this.shippment.price))
                ? Number(this.shippment.price)
                : 0;
            return shippmentPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        totalPrice() {
            const subtotal = !isNaN(Number(this.subtotal)) ? Number(this.subtotal) : 0;
            const shippmentPrice = this.shippment && !isNaN(Number(this.shippment.price))
                ? Number(this.shippment.price)
                : 0;
            const sumValues = subtotal + shippmentPrice;
            return sumValues.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    },
    methods: {
        updateShippment(selectedShippment, zip_code, delivery_name) {
            this.shippment = selectedShippment;
            this.billing_address.zip_code = zip_code;
            this.billing_address.city = selectedShippment.city;
            this.delivery_name = delivery_name;
            return this.finalValue(this.shippment);

        },
        finalValue(shippment) {
            const sumValue = parseFloat(shippment.price);

            return sumValue
        },
        updateAddressFields(newItem) {
            console.log(newItem);
            this.billing_address.shippment_address = newItem.endereco;
            this.billing_address.shippment_city = newItem.city ?? '';
            this.billing_address.select_uf = newItem.UF,
                console.log(this.billing_address.shippment_address);
        },
        confirmNext() {
            const data = {
                billing_address: this.billing_address,
                email: this.email ?? this.customer.email,
                shippment: this.shippment,
            }
            return this.$emit('confirmNext', data);
           
        },
        removeItemDialog(item) {
            this.index = this.carts.findIndex(cartItem => cartItem.cart_item_id === item.cart_item_id);
            this.editedItem = Object.assign({}, item);
            this.removeDialog = true;
        },
        removeItemConfirm() {

            this.carts.splice(this.index, 1);
            this.closeRemoveItemDialog();

        },
        closeRemoveItemDialog() {
            this.removeDialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, '');
                this.index = -1;
            });
        }
    }
}
</script>

<style scoped>
.avatar-stack {
    position: absolute;
    transition: transform 0.3s ease;

}

.avatar-stack:hover {
    transform: translateY(-10px);
}

.container-relative {
    position: relative;
    height: 100%;

}

.container-absolute {
    position: relative;
    margin-left: 50%;
}

.text-caption {
    display: flex;
    margin-left: 32%;
    /* Espaço entre nome e preço */
}
</style>