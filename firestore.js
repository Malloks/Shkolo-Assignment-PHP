//import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
//import { getFirestore as createFirestore, collection, addDoc, doc, setDoc, getDocs, query, where, getDoc, orderBy } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-firestore.js"
//
//const firebaseConfig = {
//    apiKey: "AIzaSyBd3eW_tQVeAOI3gqCU4Me2Dj4PjmTdnLk",
//    authDomain: "shkolo-task-ea556.firebaseapp.com",
//    projectId: "shkolo-task-ea556",
//    storageBucket: "shkolo-task-ea556.appspot.com",
//    messagingSenderId: "704639697392",
//    appId: "1:704639697392:web:0758cf5c765540f3b89d35"
//};
//
//const app = initializeApp(firebaseConfig);
//const db = createFirestore(app);
//const collectionRef = collection(db, "hyperlinks");
//
//export function getFirestore() {
//    return db;
//}
//
//export async function insertHyperlink({
//    title,
//    url,
//    color,
//    position
//}) {
//    return addDoc(collectionRef, {
//        title,
//        url,
//        color,
//        position,
//        created_on: new Date(),
//        updated_on: new Date(),
//        deleted_on: null
//    }).then((docRef) => {
//        console.log("Document written with ID: ", docRef.id);
//        return docRef.id
//    }).catch((error) => {
//        console.error("Error adding document: ", error);
//    });
//}
//
//export async function updateHyperlink(docId, updatedData) {
//    updatedData = { ...updatedData };
//    //in order to forbid updating created_on
//    delete updatedData.created_on
//    const docRef = doc(db, "hyperlinks", docId);
//
//
//
//    return setDoc(docRef, {
//        ...updatedData,
//        updated_on: new Date()
//    }).then(() => {
//        console.log("Document successfully updated!");
//    }).catch((error) => {
//        console.error("Error updating document: ", error);
//
//        alert('Try again later')
//
//    });
//}
//
//export async function getAllHyperlinks() {
//    const q = query(collection(db, "hyperlinks"), where('deleted_on', '==', null));
//
//    const querySnapshot = await getDocs(q);
//
//
//    return querySnapshot.docs.map(el => ({
//        id: el.id,
//        ...el.data()
//    }))
//}
//
//export async function getHyperlink(id) {
//    const docRef = doc(db, "hyperlinks", id);
//    const docSnap = await getDoc(docRef);
//    return {
//        id,
//        ...docSnap?.data()
//    }
//}
//