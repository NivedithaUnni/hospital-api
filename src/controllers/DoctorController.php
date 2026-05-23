<?php

class DoctorController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    // GET ALL DOCTORS
    public function getAll($request, $response)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->query("SELECT * FROM doctors");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // ADD DOCTOR
    public function add($request, $response)
{
    $pdo = $this->container->get(PDO::class);
    $data = $request->getParsedBody();

    $stmt = $pdo->prepare("INSERT INTO doctors (name, specialization, email) VALUES (?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['specialization'],
        $data['email']
    ]);

    $response->getBody()->write(json_encode(["message" => "Doctor added"]));
    return $response->withHeader('Content-Type', 'application/json');
}

    // UPDATE DOCTOR
public function update($request, $response, $args)
{
    $pdo = $this->container->get(PDO::class);
    $data = $request->getParsedBody();

    $stmt = $pdo->prepare("
        UPDATE doctors 
        SET name = ?, specialization = ?, email = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $data['name'],
        $data['specialization'],
        $data['email'],
        $args['id']
    ]);

    $response->getBody()->write(json_encode(["message" => "Doctor updated"]));
    return $response->withHeader('Content-Type', 'application/json');
}

    // DELETE DOCTOR
    public function delete($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->prepare("DELETE FROM doctors WHERE id=?");
        $stmt->execute([$args['id']]);

        $response->getBody()->write(json_encode(["message" => "Doctor deleted"]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}