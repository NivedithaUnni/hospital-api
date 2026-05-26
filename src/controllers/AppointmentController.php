<?php

class AppointmentController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    // =========================
    //  CREATE APPOINTMENT (WITH SLOT CHECK)
    // =========================
    public function create($request, $response)
    {
        $pdo = $this->container->get(PDO::class);
        $data = $request->getParsedBody();

        $patient_id = $data['patient_id'] ?? null;
        $doctor_id = $data['doctor_id'] ?? null;
        $appointment_date = $data['appointment_date'] ?? null;

        if (!$patient_id || !$doctor_id || !$appointment_date) {
            $response->getBody()->write(json_encode([
                "error" => "All fields required"
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        // ✅ Check patient exists
        $check = $pdo->prepare("SELECT id FROM patients WHERE id=?");
        $check->execute([$patient_id]);

        if (!$check->fetch()) {
            $response->getBody()->write(json_encode([
                "error" => "Patient does not exist"
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        // 🚨 CHECK SLOT ALREADY BOOKED
        $checkSlot = $pdo->prepare("
            SELECT id FROM appointments 
            WHERE appointment_date = ?
        ");
        $checkSlot->execute([$appointment_date]);

        if ($checkSlot->fetch()) {
            $response->getBody()->write(json_encode([
                "error" => "Slot already booked"
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        // ✅ INSERT
        $stmt = $pdo->prepare("
            INSERT INTO appointments 
            (patient_id, doctor_id, appointment_date, status) 
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $patient_id,
            $doctor_id,
            $appointment_date,
            'Scheduled'
        ]);

        $response->getBody()->write(json_encode([
            "message" => "Appointment created successfully"
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    // =========================
    //  GET ALL APPOINTMENTS
    // =========================
    public function getAll($request, $response)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->query("
            SELECT a.*,
                   p.name AS patient_name,
                   d.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // =========================
    // GET ONE APPOINTMENT
    // =========================
    public function getOne($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->prepare("
            SELECT a.*,
                   p.name AS patient_name,
                   d.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
            WHERE a.id = ?
        ");

        $stmt->execute([$args['id']]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            $response->getBody()->write(json_encode([
                "error" => "Appointment not found"
            ]));
            return $response->withStatus(404)
                            ->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // =========================
    // ✅ UPDATE FULL APPOINTMENT
    // =========================
    public function update($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);
        $data = $request->getParsedBody();

        $patient_id = $data['patient_id'] ?? null;
        $doctor_id = $data['doctor_id'] ?? null;
        $appointment_date = $data['appointment_date'] ?? null;
        $status = $data['status'] ?? 'Scheduled';

        if (!$patient_id || !$doctor_id || !$appointment_date) {
            $response->getBody()->write(json_encode([
                "error" => "All fields required"
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        $stmt = $pdo->prepare("
            UPDATE appointments 
            SET patient_id = ?, doctor_id = ?, appointment_date = ?, status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $patient_id,
            $doctor_id,
            $appointment_date,
            $status,
            $args['id']
        ]);

        $response->getBody()->write(json_encode([
            "message" => "Appointment updated successfully"
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    // =========================
    // ✅ UPDATE ONLY STATUS
    // =========================
    public function updateStatus($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);
        $data = $request->getParsedBody();

        $status = $data['status'] ?? null;

        if (!$status) {
            $response->getBody()->write(json_encode([
                "error" => "Status required"
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        $stmt = $pdo->prepare("
            UPDATE appointments 
            SET status = ?
            WHERE id = ?
        ");

        $stmt->execute([$status, $args['id']]);

        $response->getBody()->write(json_encode([
            "message" => "Status updated successfully"
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    // =========================
    // ✅ DELETE APPOINTMENT
    // =========================
    public function delete($request, $response, $args)
    {
        $pdo = $this->container->get(PDO::class);

        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$args['id']]);

        $response->getBody()->write(json_encode([
            "message" => "Appointment deleted successfully"
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}