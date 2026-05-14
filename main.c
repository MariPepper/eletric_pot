// =====================================================
// INCLUDES NO TOPO
// =====================================================
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include "cJSON.h"   // biblioteca de parse JSON

// =====================================================
// STRUCTS NO TOPO
// =====================================================
typedef struct {
    int id;
    char acao[32];
    char mensagem[256];
    int tempo;
} Passo;

// =====================================================
// VARIÁVEIS GLOBAIS NO TOPO
// =====================================================
Passo passos[32];
int PASSOS_TOTAL = 0;

// =====================================================
// HARDWARE SIMULADO
// =====================================================
void display(const char *msg) {
    printf("[DISPLAY] %s\n", msg);
}

void heat_on() {
    printf("🔥 Aquecimento ON\n");
}

void heat_off() {
    printf("❄️ Aquecimento OFF\n");
}

void beep() {
    printf("🔔 BEEP\n");
}

// =====================================================
// FUNÇÃO PARA LER FICHEIRO JSON
// =====================================================
char *lerFicheiro(const char *nome) {
    FILE *f = fopen(nome, "rb");
    if (!f) return NULL;

    fseek(f, 0, SEEK_END);
    long tamanho = ftell(f);
    rewind(f);

    char *buffer = malloc(tamanho + 1);
    fread(buffer, 1, tamanho, f);
    buffer[tamanho] = '\0';

    fclose(f);
    return buffer;
}

// =====================================================
// PARSE DO JSON PARA AS STRUCTS
// =====================================================
void carregarJSON(const char *ficheiro) {

    char *texto = lerFicheiro(ficheiro);
    if (!texto) {
        printf("Erro ao abrir JSON\n");
        exit(1);
    }

    cJSON *root = cJSON_Parse(texto);
    if (!root) {
        printf("Erro ao fazer parse do JSON\n");
        exit(1);
    }

    cJSON *lista = cJSON_GetObjectItem(root, "passos");
    PASSOS_TOTAL = cJSON_GetArraySize(lista);

    for (int i = 0; i < PASSOS_TOTAL; i++) {

        cJSON *p = cJSON_GetArrayItem(lista, i);

        passos[i].id = cJSON_GetObjectItem(p, "id")->valueint;

        strcpy(passos[i].acao,
               cJSON_GetObjectItem(p, "acao")->valuestring);

        strcpy(passos[i].mensagem,
               cJSON_GetObjectItem(p, "mensagem")->valuestring);

        passos[i].tempo = cJSON_GetObjectItem(p, "tempo")->valueint;
    }

    cJSON_Delete(root);
    free(texto);
}

// =====================================================
// MOTOR DE EXECUÇÃO
// =====================================================
void executarReceita() {

    for (int i = 0; i < PASSOS_TOTAL; i++) {

        Passo p = passos[i];

        display(p.mensagem);

        if (strcmp(p.acao, "aquecer") == 0 ||
            strcmp(p.acao, "refogar") == 0 ||
            strcmp(p.acao, "adicionar") == 0 ||
            strcmp(p.acao, "juntar") == 0 ||
            strcmp(p.acao, "cozinhar") == 0) {

            heat_on();
        } else {
            heat_off();
        }

        if (p.tempo > 0) {
            sleep(p.tempo);
        }

        beep();
    }

    heat_off();
}

// =====================================================
// MAIN
// =====================================================
int main() {

    carregarJSON("receita.json");
    executarReceita();

    return 0;
}
