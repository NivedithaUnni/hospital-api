<?php

class PatientController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    // GET ALL
    public function getAll($request, $response)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->query("SELECT * FROM patients");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($patients));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // ADD
   public function add($request, $response)
{
    $pdo = $this->container->get(PDO::class);

    $data = $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    $reportName = null;

    //  Check if file uploaded
    if (isset($uploadedFiles['report'])) {
        $file = $uploadedFiles['report'];

        if ($file->getError() === UPLOAD_ERR_OK) {
            $filename = time() . "_" . $file->getClientFilename();
            $file->moveTo(__DIR__ . "/../../uploads/" . $filename);

            $reportName = $filename;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO patients (name,email,phone,report) VALUES (?,?,?,?)");
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $reportName
    ]);

    $response->getBody()->write(json_encode(["message" => "Patient added"]));
    return $response->withHeader('Content-Type', 'application/json');
}

    // GET ONE
    public function getOne($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->prepare("SELECT * FROM patients WHERE id=?");
        $stmt->execute([$args['id']]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            $response->getBody()->write(json_encode(["error" => "Not found"]));
            return $response->withStatus(404)
                            ->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($patient));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // UPDATE
    public function update($request, $response, $args)
{
    $pdo = $this->container->get(PDO::class);
    $data = $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    $reportName = null;

    if (isset($uploadedFiles['report'])) {
        $file = $uploadedFiles['report'];

        if ($file->getError() === UPLOAD_ERR_OK) {
            $filename = time() . "_" . $file->getClientFilename();
            $file->moveTo(__DIR__ . "/../../uploads/" . $filename);

            $reportName = $filename;
        }
    }

    if ($reportName) {
        $stmt = $pdo->prepare("UPDATE patients SET name=?,email=?,phone=?,report=? WHERE id=?");
        $stmt->execute([$data['name'], $data['email'], $data['phone'], $reportName, $args['id']]);
    } else {
        $stmt = $pdo->prepare("UPDATE patients SET name=?,email=?,phone=? WHERE id=?");
        $stmt->execute([$data['name'], $data['email'], $data['phone'], $args['id']]);
    }

    $response->getBody()->write(json_encode(["message" => "Updated"]));
    return $response->withHeader('Content-Type', 'application/json');
}

    // DELETE
    public function delete($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->prepare("DELETE FROM patients WHERE id=?");
        $stmt->execute([$args['id']]);

        $response->getBody()->write(json_encode(["message" => "Deleted"]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}