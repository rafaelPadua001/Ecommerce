class CartStorage {
    constructor(){
        this.cart = null;
    }

    setCart(cart){
        this.cart = cart;
    }

    getCart(){
        return this.cart;
    }

    clearCart(){
        this.cart = null;
    }

}

export default new CartStorage();