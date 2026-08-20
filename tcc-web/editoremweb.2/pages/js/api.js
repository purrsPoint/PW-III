// Envia o código pro aluno testar antes de enviar de vez
export async function enviarCodigo(codigo, entrada) {
    const response = await fetch("../backend/submit.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ codigo, entrada })
    });
    return await response.json();
}

// Checa o resultado da execução no Judge0 pelo token
export async function checarResultado(token) {
    const response = await fetch(`../backend/result.php?token=${token}`);
    return await response.json();
}

// Envia o código para testar com as entradas do banco de dados
export async function verificarExercicios(exercicioId, codigo) {
    const response = await fetch("../backend/verificar_resultado.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            exercicio_id: exercicioId,
            codigo: codigo
        })
    });

    // Pega a resposta em texto primeiro para evitar o erro do JSON.parse
    const texto = await response.text();

    try {
        return JSON.parse(texto);
    } catch (e) {
        // Exibe no console exatamente o que o PHP devolveu se não for JSON
        console.error("Erro retornado pelo PHP (texto bruto):", texto);
        throw new Error("Erro no backend. Abra o console (F12) para ver a resposta do PHP.");
    }
}