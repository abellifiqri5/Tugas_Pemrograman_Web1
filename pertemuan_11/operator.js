/**
 * algoritma 
 * uang belanja ada 5000
 * total belanjaan 
 * respon pelanggan=true/false
 * jika total belanjaannya lebih dari 100 maka dapat diskon 10% (selesaii)
 * jika uang belanja anda kurang dari total belanjaan maka tampilkan pesan "uang anda tidak cukup" (selesai)
 * jika uang belanjanya lebih dari total belanja maka tampilkan pesan "uang kembalian anda" (selesai)
 * jika uang belanja lebih besar dari total belanja maka tampilkan pesan "apakah mau di donasikan kemabaliannya?"
 * jika iya maka tampilkan pesan "terimakasih sudah berdonasi" #true
 * jika tidak tampilkan pesan "baiklah" #false
 * jika uang belanja anda sama dengan total belanja maka tampilkan pesan"terima kasih sudah berbelanja"(selesai)
 * 
 */

//variable awal
let uangBelanja=50000 
let totalBelanja=50000
let uangKembalian=0
let totalDiskon=0
let respoPelanggan=false

//kondisi pertama:uang belanja lebih dari total belanja dan dapat diskon
if(uangBelanja > totalBelanja && uangBelanja > 100000){
//total belanja di x dengan diskon
    totalDiskon = totalBelanja * 0.10;
//uang belanja di kurang uang diskon
uangKembalian = uangBelanja - totalDiskon;
console.log("uang kembalian anda di tambah dengan diskon sebesar 10%: "+ uangKembalian);

}
else if(uangBelanja > totalBelanja && uangBelanja <= 100000  ){
uangKembalian = uangBelanja - totalBelanja
console.log("uang kembalian anda :"+ uangKembalian);
// code donasi pr

}
else if(uangBelanja == totalBelanja){
    console.log("uang pas");
    
}


else{
    console.log("uang belanjaan anda tidak cukup");
    
}

// if (dapat diskon 10%){
//     console.log("selamat anda dapat diskon 10%");
//     console.log("uang kembalian anda");
//     console.log("terima kasih sudah berdonasi");
    
// } 

//     else{
//         console.log("uang tidak cukup");
//         console.log("apakah mau di donasikan uang kembaliannya");
//         console.log("terima kasih sudah berbelanja");
        
        
        
// }






// let totalbelanja=20000;
// let saldoKurang=250000;

// if (belanja > diskon) {
//     console.log("selamat anda dapat diskon");
    
// } else{
//     console.log("maaf anda tidak dapat diskon");
    

// }