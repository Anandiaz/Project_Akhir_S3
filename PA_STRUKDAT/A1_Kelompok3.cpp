#include <iostream> // Header dan library yang dibutuhkan untuk program ini
#include <fstream>
#include <string>
#include <sstream>
#include <windows.h>
#include <iomanip>
#include <limits>
using namespace std;

#define GREEN 10 // Pendefinisian pada kode warna untuk outpot console
#define RED 12
#define PURPLE 5
#define BLUE 11
#define RED2 13
#define NETRAL 7

struct Antri{ // Pengelompokan variabel yang dinaungi oleh satu nama yang sama
	string username;
	string password;
	Antri *next; // Pointer yang digunakan untuk ke elemen berikutnya
};

struct Node { 
	int nomor;
	string ktp;
	string nama;
	string hp;
	string asal;
	Node *next;
};

struct Kamar {
	int no;
	string status;
	Kamar *next;
	Kamar *prev; // Pointer yang digunakan untuk ke elemen sebelumnya
};

int banyak_kamar=0; 
fstream file; // Pendeklarasian file yang digunakan untuk membaca atau menulis file
int Kamar_kosong=0;


void menu_awal(Node *head, Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear); // Fungsi prototype menu awal, untuk menampilkan opsi menu dan pemrosesan inputan
void menu_admin(Node *head,Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear); // Fungsi prototype menu awal, untuk memanipulasi data penyewa dan kamar
void dequeue(Antri **front, Antri **rear) ; // Fungsi prototype yang bertujuan untuk penghapusan elemen dari bagian depan antrian
int searchUser(Antri *front, string pat);

// validasi full angka atau tidak
bool iniAngka(string num) {
	for(int i=0; i<num.length(); i++) {
		if(isdigit(num[i])) {
			continue;
		} else {
			return false;
			break;
		}
	}
	return true;
}

// Fungsi pergerakan arrow untuk pemilihan kamar
HANDLE console = GetStdHandle(STD_OUTPUT_HANDLE);
COORD CursorPosition; 
void xy(int x, int y) {
	CursorPosition.X = x;
	CursorPosition.Y = y;
	SetConsoleCursorPosition(console,CursorPosition); 
}

// Fungsi untuk mendefinisikan warna
void color(unsigned short kodeWarna) {
    HANDLE hCon = GetStdHandle(STD_OUTPUT_HANDLE);
    SetConsoleTextAttribute(hCon, kodeWarna);
}

// FILE EKSTERNAL
void LoadFileKamar(Kamar **head, Kamar **tail)
{
	string nomor;
	
    file.open("Kamar.csv",ios::in);
    string line;
    if (file.peek() == std::ifstream::traits_type::eof()) {
        cout << "File is empty" << endl;
        return;
    }
    while (getline(file, line))
    {
        stringstream ss(line);
        Kamar *newKamar = new Kamar;
		
		getline(ss, nomor, ';');
        getline(ss, newKamar->status,'\n');
        
        stringstream nomor_str(nomor);
        nomor_str >> newKamar->no;
        ss.ignore();

        newKamar->next = NULL;

        if (*head == NULL)
        {
            *head = newKamar;
            *tail = newKamar;
        }
        else
        {
            newKamar->prev = *tail;
            (*tail)->next = newKamar;
            *tail = newKamar;
        }
        banyak_kamar++;
    }

    file.close();
}

void SaveFileKamar(Kamar *head)
{
	file.open("Kamar.csv",ios::out | ios::trunc);
    Kamar *current = head;
    while (current != nullptr)
    {
        file << current->no << ";"
             << current->status << endl;

        current = current->next;
    }

    file.close();
}

void LoadFilePenyewa(Node **head)
{
    string nomor;
    file.open("Penyewa.csv",ios::in);
    string line;
    if (file.peek() == std::ifstream::traits_type::eof()) {
        cout << "File is empty" << endl;
        return;
    }
    while (getline(file, line))
    {
        stringstream ss(line);
        Node *newPenyewa = new Node;
		
		getline(ss, nomor,';');
		getline(ss, newPenyewa->ktp, ';');
        getline(ss, newPenyewa->nama, ';');
        getline(ss, newPenyewa->hp, ';');
        getline(ss, newPenyewa->asal,'\n');
        
        stringstream nomor_str(nomor);
        nomor_str >> newPenyewa->nomor;
		ss.ignore();

        newPenyewa->next = nullptr;

        if (*head == nullptr)
        {
            *head = newPenyewa;
        }
        else
        {
            newPenyewa->next = *head;
            *head = newPenyewa;
        }
    }

    file.close();
}

void SaveFilePenyewa(Node *head)
{
	
    file.open("Penyewa.csv",ios::out | ios::trunc);
    Node *current = head;
    while (current != nullptr)
    {
        file << current->nomor << ";"
			 << current->ktp << ";"
             << current->nama << ";"
             << current->hp << ";"
			 << current->asal << endl;

        current = current->next;
    }

    file.close();
}

// Fungsi penyewa
void addfirst(Node **head, int kamar) // done
{ 
	
	string ktp,hp;
	Node *Nodebaru = new Node;
	
	Nodebaru->nomor = kamar;
	color(BLUE);
	xy(7, 3); cout<<"=============================================";
	xy(20, 3);cout<<" Tambah Penyewa " ;
	xy(10, 4);cout<<"Masukkan No KTP ; ";
	cin>>ktp;
	try {
		while (iniAngka(ktp) != true) {
			cout << "Mohon input angka ";
			cin.clear();
			cin.ignore();
			cin >> ktp;
		}
		throw ktp;
	} catch(string ktp) {
		Nodebaru->ktp = ktp;
		cin.ignore();
	}
	xy(10, 5);cout<< "Masukkan Nama : ";
	cin.ignore();
	getline(cin,Nodebaru->nama);
	xy(10, 6);cout<<"Masukkan No Hp : ";
	cin>>hp;
	try {
		while (iniAngka(hp) != true) {
			cout << "Mohon input angka ";
			cin.clear();
			cin.ignore();
			cin >> hp;
		}
		throw hp;
	} catch(string hp) {
		Nodebaru->hp = hp;
		cin.ignore();
	}
	xy(10, 7);cout<<"Asal Daerah : ";
	cin.ignore();
	getline(cin,Nodebaru->asal);
	
	Nodebaru->next = *head;
	*head= Nodebaru;

}

void deleteSpecific(Node **head, int position){
	if(!head){
		xy(10, 4); cout << "Tidak Ada Data Untuk Dihapus.";
		xy(10, 6); system("pause");
		return;
	}
	
	if (position < 1){
		xy(10, 4); cout << "Posisi Invalid.";
		xy(10, 6); system("pause");
		return;
	}
	
	Node *temp = *head;
	Node *prev = NULL;
	
	if (temp != NULL && temp->nomor == position) 
    { 
        *head = temp->next;  
        delete temp;         
        return; 
    }
    
	while (temp != NULL && temp->nomor != position) 
    { 
        prev = temp; 
        temp = temp->next; 
    } 
    
    if(temp==NULL) return;
    
    prev->next = temp->next;
    
    delete temp;
	
	
}

// Fungsi antrian
void enqueue(Antri **front, Antri **rear) // Fungsi yang digunakan untuk menambah antrian
{
	string User,Pass;
	Antri *AntriBaru = new Antri();
	
	system("cls");
	color(BLUE);
	xy(7, 3); cout<<"=============================================";
	xy(18, 3);cout<<" Tambah Antrian " ;
	xy(10, 4);cout << "Masukkan Username: ";
	cin.ignore();
	getline(cin, User);
	xy(10, 5);cout << "Masukkan Password: ";
	cin >> Pass;
	
	while(searchUser(*front,User) != -2){
		system("cls");
		xy(10, 6);cout << "Username Sudah Ada\n";
		xy(10, 9);system("pause");
		system("cls");
		xy(10, 5);cout << "Masukkan Username: ";
		cin.ignore();
		getline(cin, User);
		xy(10, 6);cout << "Masukkan Password: ";
		cin >> Pass;
	}
	
	AntriBaru->username = User;
	AntriBaru->password = Pass;
	
	color(GREEN);
	xy(10, 12); cout << "Berhasil ditambahkan";
	
	if (*front == NULL) {
	  	*front = AntriBaru;
	  	*rear = AntriBaru;
		return;
	} else {
		(*rear)->next = AntriBaru;
	}
	*rear = AntriBaru;
}

void dequeue(Antri **front, Antri **rear) 
{
	if (*front == NULL) {
		color(RED);
		xy(10, 4); cout << "Antrian Kosong";
		xy(10, 6); system("pause");
		system("cls");
		return;
	}
	Antri *temp = *front;
	
	if ((*front)->next != NULL) {
		*front = (*front)->next;
	} else {
		*front = NULL;
		*rear = NULL;
	}
	
	delete temp;
	
	color(GREEN);
	xy(10, 4); cout << "Antrian Berhasil Dihapus";
	xy(10, 6); system("pause");
	system("cls");
}

// Fungsi kamar
void AddKamar(Kamar **head, Kamar **tail) // Fungsi penambahan kamar oleh admin
{
    Kamar *newKamar = new Kamar;
    newKamar->no = banyak_kamar + 1;
    newKamar->status = "Kosong";
    
    newKamar->next = nullptr;
    newKamar->prev = *tail;
    
    if (*tail)
        (*tail)->next = newKamar;
    else
        *head = newKamar;
    *tail = newKamar;
    
    banyak_kamar++;
    
    color(GREEN);
    xy(10, 4); cout<<"Kamar Berhasil Ditambahkan";
    xy(10, 6); system("pause");
    system("cls");
}

void KosongkanKamar(Node *head,Kamar *head2, Kamar *tail,int position) // Fungsi update kamar
{
    Kamar *current = head2;
    int currentPosition = 1;

    while (current && currentPosition < position)
    {
        current = current->next;
        currentPosition++;
    }

    if (!current)
    {
    	color(RED);
        xy(10, 3);cout << "Posisi Invalid, Gagal Mengupdate Kamar." << endl;
        xy(10, 5); system("pause");
        system("cls");
        return;
    }

    current->no = position;
    deleteSpecific(&head,position);
    current->status = "Kosong";
	
	color(BLUE);
    xy(10, 3);cout << "Kamar Pada Posisi " << position << " Telah Dikosongkan" << endl;
    xy(10, 5);system("pause");
    system("cls");
}

void IsiKamar(Node *Head,Kamar *head, Kamar *tail, int position)
{
    Kamar *current = head;
    int currentPosition = 1;

    while (current && currentPosition < position)
    {
        current = current->next;
        currentPosition++;
    }

    if (!current)
    {
        xy(10, 4); cout << "Posisi Invalid, Gagal Mengupdate Kamar.";
		xy(10, 6); system("pause");
        return;
    }

    current->no = position;
    current->status = "Terisi";
    
    
}


// Fungsi untuk menampilkan antrian, kamar, dan penyewa
void displayAntri(Antri *top){ // Fungsi untuk menampilkan antrian paling pertama
	system("cls");
	Antri *temp = top;
	int y = 4;
	xy(10, 3);cout<< "--------= URUTAN ANTRI =--------";
	while (temp != NULL){
		xy(10, y);cout << "Username : " << temp->username;
		xy(10, y+=1);cout << "Password : " << temp->password;
		temp = temp->next;
		y+=2;
	}
	xy(10, y); system("pause");
    system("cls");
}

void displayKamar(Kamar *Head2) // Fungsi untuk menampilkan data kamar 
{
	system("cls");
	color(GREEN);
    xy(10, 3);cout << "============ Kamar ============";
    int position = 0;
    int y =5;
    Kamar *current = Head2;
    while (current != nullptr)
    {
        xy(10, y);cout << "Posisi: " << position;
        xy(10, y+=1);cout << "Nomor Kamar: " << current->no;
        xy(10, y+=1);cout << "Status Kamar: " << current->status;
        xy(10, y+=1);cout << "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        
        current = current->next;
        position++;
        y+=2;
    }
    xy(10, y); system("pause");
    system("cls");
}

void displayPenyewa(Node *head){ // Fungsi untuk menampilkan data penyewa
	if (head == NULL){
		xy(10, 4); cout << "Data Kosong, Isi Terlebih Dahulu.";
		xy(10, 6); system("pause");
        return;
	}
	int y =5;
	Node *temp = head;
	while (temp != NULL){
		color(BLUE);
		xy(10, y);cout<< "Kamar ke- "<<temp->nomor;
		xy(10, y+=1);cout<<"KTP : "<<temp->ktp;
		xy(10, y+=1);cout<<"Nama : "<<temp->nama;
		xy(10, y+=1);cout<<"HP : "<<temp->hp;
		xy(10, y+=1);cout<<"Asal : "<<temp->asal;
		temp = temp->next;
		y+=2;
	}
	cout<<"\n";
	xy(10, y);system("pause");
}


// sorting data
Node *SortedMerge (Node *a, Node*b, string tipe){
	Node *result = nullptr;
	if (a == nullptr){
		return (b);
	}
	else if(b == nullptr){
		return (a);
	}
	bool bandingData;
	if(tipe == "nama_asc"){
		bandingData = a -> nama <= b -> nama;
	}
	else if(tipe == "nama_desc"){
		bandingData = a -> nama >= b -> nama;
	}
	else if(tipe == "asal_asc"){
		bandingData = a -> asal <= b -> asal;
	}
	else if(tipe == "asal_desc"){
		bandingData = a -> asal >= b -> asal;
	}
		if(bandingData == true){
			result = a;
			result -> next = SortedMerge(a -> next, b, tipe);
		}
		else{
			result = b;
			result -> next = SortedMerge(a, b -> next, tipe);
		}

		return (result);
}

void MergeSort(Node **headRef, string tipe){
    Node *t = *headRef;
    Node *a;
    Node *b;
    if ((t == NULL) || (t -> next == NULL)) return;
    Node *fast;
    Node *slow;
    slow = t;
    fast = t->next;
    while (fast != NULL){
        fast = fast -> next;
        if (fast != NULL){
            slow = slow -> next;
            fast = fast -> next;
        }
    }
    a = t;
    b = slow -> next;
    slow -> next = NULL;
    MergeSort(&a, tipe);
    MergeSort(&b, tipe);
    *headRef = SortedMerge(a, b, tipe);
};

void sorting(Node *head,Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear){
    int cari;
    xy(10,5);cout << "============== Sorting ============";
    xy(10,6);cout << "1. Nama Penyewa Ascending" ;
    xy(10,7);cout << "2. Nama Penyewa Descending" ;
    xy(10,8);cout << "3. Asal Penyewa Ascending" ;
    xy(10,9);cout << "4. Asal Penyewa Descending" ;
	xy(10,10);cout << "==================================";
    xy(10,12);cout << "Masukkan Pilihan >> ";
    cin >> cari;
    
    if(cari == 1){
        system("cls");
        MergeSort(&head, "nama_asc");
        system("pause");
        system("cls");
    }

    else if(cari == 2){
        system("cls");
        MergeSort(&head, "nama_desc");
        system("pause");
        system("cls");
    }

    else if(cari == 3){
        system("cls");
        MergeSort(&head, "asal_asc");
        system("pause");
        system("cls");
    }

    else if(cari == 4){
        system("cls");
        MergeSort(&head, "asal_desc");
        system("pause");
        system("cls");
    }
    menu_admin(head,Head2,Tail,front,rear);
}


// Cari data
const int NO_OF_CHARS = 256;
// Fungsi untuk mengisi array badChar[] untuk memberikan informasi
// Terakhir kemunculan karakter yang tidak cocok
void badCharHeuristic(string str, int size, int
                      badChar[NO_OF_CHARS]) {
	for (int i = 0; i < NO_OF_CHARS; i++) {
		badChar[i] = -1;
	}

// Isi nilai aktual dari badChar untuk karakter yang ada dalam str
	for (int i = 0; i < size; i++) {
		badChar[(int) str[i]] = i;
	}
}

// Fungsi pencarian string dengan Boyer-Moore Bad Character Heuristic

int searchAkun(Antri *head, string user, string pass) {
    int cUser = user.size();
    int cPass = pass.size();
    Antri *temp = head;
    int pos = 0;
    while (temp != NULL) {
        int n1 = temp->username.size();
        int n2 = temp->password.size();
        int badChar[NO_OF_CHARS];
        badCharHeuristic(user, cUser, badChar);
        badCharHeuristic(pass, cPass, badChar);
        int s1 = 0, s2 = 0; // s1 dan s2 adalah shifts dari pattern atau pola yang digunakan untuk username dan password
        while (s1 <= (n1 - cUser) && s2 <= (n2 - cPass)) {
            int j = cUser - 1, k= cPass - 1;
            while (j >= 0 && user[j] == temp->username[s1 + j] ){
                j--;
            }
            while (k>=0 && pass[k] == temp->password[s2 + k]){
            	k--;
			}
            if (j < 0 && k < 0) {
                return pos;
                break;
            } else {
                s1 += max(1, j - badChar[temp->username[s1 + j]]);
                s2 += max(1, k - badChar[temp->password[s2 + k]]);
            }
        }
        temp = temp->next;
        pos++;
    }
    return -1;
}

int searchUser(Antri *front, string pat) {
	int m = pat.size();
	Antri *temp = front;
	int pos = 0;
	while (temp != NULL) {
		int n = temp->username.size();
		int badChar[NO_OF_CHARS];
		badCharHeuristic(pat, m, badChar);
		int s = 0; // s adalah shift dari pola sehubungan dengan teks
		while (s <= (n - m)) {
			int j = m - 1;
			while (j >= 0 && pat[j] == temp->username[s + j]) {
				j--;
			}
			if (j < 0) {
				return pos;
				break;
			} else {
				s += max(1, j - badChar[temp->username[s + j]]);
			}
		}
            temp = temp->next;
            pos++;
	}
	return -2;
}


// Fungsi pemilihan kamar pada menu penyewa
void buat_kamar(int kamar,Kamar *Head2){
	system("cls");
	color(NETRAL);
	int i=1;
	int max=5;
	int x=5;
	int y=2;
	int y_lantai;
	int jumlah = kamar;
	int lantai=1;
	int merah=0;
	Kamar *temp = Head2;
	while (kamar > 0){
		if(max % 10 !=0) {
				y_lantai=y-1;
				xy(5,y_lantai);cout<<"Lantai"<<lantai<<endl;
				lantai++;
			}
		if(kamar >= 5) {	
			for(i;i<=max;i++){
				if (temp->status != "Kosong"){
					xy(x,y);color(RED);cout << setw(2) << setfill('0') << i << " ";color(NETRAL);
					x+=3;
					i++;
				}
				xy(x,y);cout << setw(2) << setfill('0') << i << " ";
				x+=3;
				temp = temp->next;
			}
			y+=1;
			x=5;
			if(max % 10 == 0){
				y+=2;
			}
			
			cout << endl;
			kamar-=5;
			max+=5;
		}
		else if ( 0 > kamar <5){
			for(i;i<=jumlah;i++){
				if (temp->status != "Kosong"){
					xy(x,y);color(RED);cout << setw(2) << setfill('0') << i << " ";color(NETRAL);
					x+=3;
					i++;
				}
				xy(x,y);cout << setw(2) << setfill('0') << i << " ";
				x+=3;
				temp = temp->next;
			}
			kamar = 0;
		}
		
	}
}

int pilih_kamar(Kamar *Head2){
	int x = 1;
	int y = 0;
	int X=5;
	int Y=2;
	int kamar,max,horizontal;
	kamar = banyak_kamar;
	max = kamar /5;
	horizontal = kamar % 5;
	
	buat_kamar(kamar,Head2);
	while(true){
		system("pause>nul");
		if(GetAsyncKeyState(VK_DOWN) && y!= max){
			buat_kamar(kamar,Head2);
			y+=1;
			if(x > horizontal and y == max){
				x=horizontal;
				X=2;
				X+=3*horizontal;
			}
			if(y%2==0){
				xy(X,Y+=3);color(GREEN);cout<< setw(2) << setfill('0') <<y*5 + x;color(NETRAL);
			}
			else {
				xy(X,Y+=1);color(GREEN);cout<< setw(2) << setfill('0') <<y*5 + x;color(NETRAL);
			}
			
		}
		if(GetAsyncKeyState(VK_RIGHT) && x!= 5){
			buat_kamar(kamar,Head2);
			x+=1;
			if(x >= horizontal and y == max){
				x=horizontal;
				X=-1;
				X+=3*horizontal;
			}
			xy(X+=3,Y);color(GREEN);cout<< setw(2) << setfill('0') <<y*5 + x;color(NETRAL);
		}
		if(GetAsyncKeyState(VK_UP) && y!= 0){
			buat_kamar(kamar,Head2);
			y-=1;
			if(y%2==0){
				xy(X,Y-=1);color(GREEN);cout<< setw(2) << setfill('0') <<y*5 + x;color(NETRAL);
			}
			else {
				xy(X,Y-=3);color(GREEN);cout<< setw(2) << setfill('0') <<y*5 + x;color(NETRAL);
			}
		}
		if(GetAsyncKeyState(VK_LEFT) && x!= 1){
			buat_kamar(kamar,Head2);
			x-=1;
			xy(X-=3,Y);color(GREEN);cout<< setw(2) << setfill('0') <<y*5 + x;color(NETRAL);
		}
		if(GetAsyncKeyState(VK_RETURN) & 0x8000) {
			buat_kamar(kamar,Head2);
			system("cls");
			return y*5 + x;
		}
	}
	
}

// Fungsi untuk kamar dengan keadaan kosong
void hitung_kosong(Kamar *Head2){
	Kamar_kosong = 0;
	Kamar *temp = Head2;
	while(temp != NULL){
		if(temp->status == "Kosong"){
		Kamar_kosong++;
		}
	temp = temp->next;
	}
}

//CEK TERISI
bool cek_terisi(Kamar *Head2,int position){
	Kamar *temp = Head2;
	while (temp != NULL){
		if(temp->status != "Kosong" && temp->no == position){
		return true;
		}
		temp = temp->next;
	}
	return false;
}

// Menu
void menu_register(Node *head,Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear) {
	string pilih,Pass,User;;
	
	system("cls");
	color(GREEN);
	xy(7, 3); cout<<"=============================================";
	xy(22, 3);cout<<" Menu Register " ;
	xy(10, 4); cout<<"[1] Bikin Akun";
	xy(10, 5); cout<<"[2] Lihat Urutan";
	xy(10, 6); cout<<"[3] List Antrian";
	xy(10, 7); cout<<"[4] Kembali";
	xy(7, 8); cout<<"=============================================";
	xy(10, 9); cout<<" Masukkan Pilihan Anda: ";
	
	cin >> pilih;
	if (pilih == "1") {
		hitung_kosong(Head2);
		if(banyak_kamar ==0){
			system("cls");
			xy(10, 5);cout<<"Kamar belum dibuat\n";
			color(NETRAL);
			xy(10, 7); system("pause");
			menu_awal(head,Head2,Tail,front,rear);
		}
		if(Kamar_kosong > 0){
			enqueue(&front,&rear);
			system("cls");
			xy(10, 5);cout<<"Akun Berhasil Ditambahkan\n";
			color(NETRAL);
			xy(10, 7); system("pause");
			menu_register(head,Head2,Tail,front,rear);
		}
		system("cls");
		xy(10, 5);cout<<"Kamar Tidak ada yang kosong\n";
		color(NETRAL);
		xy(10, 7); system("pause");
		menu_awal(head,Head2,Tail,front,rear);
		
	}
	else if (pilih == "2") {
		system("cls");
		xy(10, 5);cout <<"Masukkan Username: ";
		cin >> User;
		xy(10, 6);cout << "Masukkan Password: ";
		cin.ignore();
		getline(cin, Pass);
		if(searchAkun(front,User,Pass)!=-1){
			system("cls");
			xy(10, 5);cout<<"Akun Ditemukan pada Urutan ke - " <<searchAkun(front,User,Pass) <<endl ;
			color(NETRAL);
			xy(10, 7); system("pause");
			
			menu_register(head,Head2,Tail,front,rear);
		}
		system("cls");
		xy(10, 5);cout<<"Akun tidak ditemukan\n";
		color(NETRAL);
		xy(10, 7); system("pause");
		
		menu_register(head,Head2,Tail,front,rear);
	}
	else if (pilih == "3") {
		menu_register(head,Head2,Tail,front,rear);
		displayAntri(front);
		xy(10, 6);
		system("pause");
	}
	else if (pilih == "4") {
		menu_awal(head,Head2,Tail,front,rear);
	}
	else {
		color(RED);
		xy(10, 10); cout << "Pilihan Tidak Valid";
		color(NETRAL);
		xy(10, 12); system("pause");
		menu_register(head,Head2,Tail,front,rear);
	}
}

void menu_penyewa(Node *head,Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear){
	string pilih;
			
	system("cls");
	color(GREEN);
	xy(7, 3); cout<<"=============================================";
	xy(22, 3);cout<<" Menu Penyewa " ;
	xy(10, 4); cout<<"[1] Pilih Kamar";
	xy(10, 5); cout<<"[2] Kembali";
	xy(7, 6); cout<<"=============================================";
	xy(10, 7); cout<<" Masukkan Pilihan Anda: ";
	
	cin >> pilih;
	system("cls");
	if (pilih == "1") {
        system("cls");
		int posisi = pilih_kamar(Head2);
		if(cek_terisi(Head2,posisi) == true or posisi <= 0){
			color(RED);
			xy(10, 10); cout << "Kamar Sudah Terisi";
			color(NETRAL);
			xy(10, 12); system("pause");
			menu_penyewa(head,Head2,Tail,front,rear);
		}
		else{
			IsiKamar(head,Head2,Tail,posisi);
			addfirst(&head, posisi);
			SaveFilePenyewa(head);
			SaveFileKamar(Head2);
			system("cls");
			dequeue(&front,&rear);
		    system("cls");
		    xy(10, 4); cout << "Kamar Pada Posisi " << posisi << " Berhasil Di isi.";
			xy(10, 6); system("pause");
			system("cls");
			menu_awal(head,Head2,Tail,front,rear);
		}
		

	}
	else if (pilih == "2") {
		menu_awal(head,Head2,Tail, front, rear);
	}
	else {
		color(RED);
		xy(10, 10); cout << "Pilihan Tidak Valid";
		color(NETRAL);
		xy(10, 12); system("pause");
		menu_penyewa(head,Head2,Tail,front,rear);
	}
}

void menu_admin(Node *head,Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear){
	string pilih;
	
	system("cls");
	color(GREEN);
	xy(7, 3); cout<<"=============================================";
	xy(22, 3);cout<<" Menu Admin" ;
	xy(10, 4); cout<<"[1] Tambah Kamar";
	xy(10, 5); cout<<"[2] Ubah Status Kamar";
	xy(10, 6); cout<<"[3] Lihat Data Kamar";
	xy(10, 7); cout<<"[4] List Penyewa";
	xy(10, 8); cout<<"[5] Sorting";
	xy(10, 9); cout<<"[6] Kembali";
	xy(7, 10); cout<<"=============================================";
	xy(10,11); cout<<"Masukkan Pilihan Anda: ";
	cin >> pilih;
		system("cls");
	if (pilih == "1") {
		AddKamar(&Head2,&Tail);
		SaveFileKamar(Head2);
		menu_admin(head,Head2,Tail,front,rear);

	}
	else if (pilih == "2") {
		int posisi = pilih_kamar(Head2);
		KosongkanKamar(head,Head2,Tail,posisi);
		SaveFileKamar(Head2);
		SaveFilePenyewa(head);
		xy(10, 10); cout << "Kamar Berhasil Dikosongkan";
		color(NETRAL);
		xy(10, 12); system("pause");
		menu_admin(head,Head2,Tail,front,rear);
	}
	else if (pilih == "3") {
		xy(10, 10);displayKamar(Head2);
		menu_admin(head,Head2,Tail,front,rear);
	}
	else if (pilih == "4") {
		xy(10, 10);displayPenyewa(head);
		menu_admin(head,Head2,Tail, front, rear);
	}
	else if(pilih == "5"){
		sorting(head,Head2,Tail, front, rear);
	}
	else if(pilih == "6"){
		menu_awal(head,Head2,Tail, front, rear);
	}
	else {
		color(RED);
		xy(10, 10); cout << "Pilihan Tidak Valid";
		color(NETRAL);
		xy(10, 12); system("pause");
		menu_admin(head,Head2,Tail,front,rear);
	}
	
}

void  menu_login(Node* head,Kamar *Head2, Kamar *Tail , Antri* front, Antri *rear){
	string Pass,User;
	system("cls");
	xy(10, 3);cout << "============= LOGIN =============";
	xy(10, 5);cout <<"Masukkan Username: ";
	cin >> User;
	xy(10, 6);cout << "Masukkan Password: ";
	cin.ignore();
	getline(cin,Pass);
	if(searchAkun(front,User,Pass)!=-1){
		system("cls");
		color(NETRAL);
		xy(10,3); cout<<"Selamat Datang, "<<User;
		xy (10, 6);system("pause");
		menu_penyewa(head,Head2,Tail,front,rear);
	}
	else if(Pass == "admin" && User == "admin"){
		system("cls");
		color(NETRAL);
		xy(10,3); cout<<"Selamat Datang, Admin!";
		xy (10, 6);system("pause");
		menu_admin(head,Head2,Tail,front,rear);
	}
	color(RED);
		xy(10, 10); cout << "Gagal Login";
		color(NETRAL);
		xy(10, 12); system("pause");
	menu_awal(head,Head2,Tail,front,rear);	
}

void menu_awal(Node *head,Kamar *Head2, Kamar *Tail ,Antri *front, Antri *rear){
	string pilih;
	
	system("cls");
	color(RED2);
	xy(7, 3); cout<<"=============================================";
	xy(26, 3);cout<<" Menu " ;
	xy(10, 4); cout<<"[1] Menu Login";
	xy(10, 5); cout<<"[2] Register";
	xy(7, 6); cout<<"=============================================";
	xy(10,7); cout<<" Masukkan Pilihan Anda: ";
	cin >> pilih;
	if(pilih == "1"){
		menu_login(head,Head2,Tail,front,rear);
	}
	else if(pilih == "2"){
		menu_register(head,Head2,Tail,front,rear);
	}
	else {
		color(RED);
		xy(10, 10); cout << "Pilihan Tidak Valid";
		color(NETRAL);
		xy(10, 12); system("pause");
		menu_awal(head,Head2,Tail,front,rear);
	}
}

int main(){
	
	Node* head = NULL;
	
	Kamar *Head2= NULL;
	Kamar *Tail = NULL; 
	
	LoadFileKamar(&Head2,&Tail);
	LoadFilePenyewa(&head);
	
	Antri* front = NULL;
	Antri* rear = NULL;

    system("cls");
    color(BLUE);
	xy(10, 4);cout << "=======================================\n";
	xy(10, 5);cout << "||                                   ||\n";
	xy(10, 6);cout << "||           Selamat Datang          ||\n";
	xy(10, 7);cout << "||                                   ||\n";
	xy(10, 8);cout << "=======================================\n";
	xy(10, 10);system("pause");
	system("cls");
	
	menu_awal(head,Head2, Tail,front,rear);
}